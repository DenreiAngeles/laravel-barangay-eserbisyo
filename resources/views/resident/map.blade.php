@extends('layouts.app')

@section('title', 'Barangay Map')
@section('page-title', 'Barangay Map')

@section('content')
<!-- Full-height container that works with your layout -->
<div style="position: fixed; top: 64px; left: 256px; right: 0; bottom: 0;">
    <div id="map-root" style="width: 100%; height: 100%;"></div>
</div>

<!-- Load React and ReactDOM -->
<script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

<script type="text/babel">
    const { useState, useEffect, useRef } = React;

    const BarangayMap = () => {
        const mapRef = useRef(null);
        const [map, setMap] = useState(null);
        const [activeLayers, setActiveLayers] = useState({
            facilities: true,
            health: true,
            security: true,
            floodZones: true,
            boundary: true
        });
        const [stats, setStats] = useState({
            facilities: 0,
            health: 0,
            security: 0,
            emergencies: 0
        });
        const [showLegend, setShowLegend] = useState(true);
        const [showInfo, setShowInfo] = useState(false);
        const [loading, setLoading] = useState(true);

        // Firebase Config
        const FIREBASE_CONFIG = {
            projectId: 'barangay-eservice-app',
            apiKey: 'AIzaSyAWOOBfSQiuqldQ6Rh9OmZyFYA5Jw6E6N0' // Replace with your actual key
        };

        const BARANGAY_CENTER = [13.643598, 121.215065];

        const layerGroups = useRef({
            facilities: null,
            health: null,
            security: null,
            floodZones: null,
            boundary: null,
            emergencies: null
        });

        // Initialize Leaflet Map
        useEffect(() => {
            if (!mapRef.current || map) return;

            const initMap = () => {
                if (!window.L) {
                    setTimeout(initMap, 100);
                    return;
                }

                const L = window.L;
                
                const newMap = L.map(mapRef.current, {
                    center: BARANGAY_CENTER,
                    zoom: 15,
                    zoomControl: false
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(newMap);

                L.control.zoom({
                    position: 'bottomright'
                }).addTo(newMap);

                layerGroups.current.facilities = L.layerGroup().addTo(newMap);
                layerGroups.current.health = L.layerGroup().addTo(newMap);
                layerGroups.current.security = L.layerGroup().addTo(newMap);
                layerGroups.current.floodZones = L.layerGroup().addTo(newMap);
                layerGroups.current.boundary = L.layerGroup().addTo(newMap);
                layerGroups.current.emergencies = L.layerGroup().addTo(newMap);

                setMap(newMap);
            };

            if (!document.getElementById('leaflet-script')) {
                const script = document.createElement('script');
                script.id = 'leaflet-script';
                script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                document.head.appendChild(script);

                const link = document.createElement('link');
                link.id = 'leaflet-css';
                link.rel = 'stylesheet';
                link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                document.head.appendChild(link);
            }

            initMap();

            return () => {
                if (map) {
                    map.remove();
                }
            };
        }, []);

        // Fetch data from Firebase
        useEffect(() => {
            if (!map) return;

            const fetchData = async () => {
                try {
                    setLoading(true);
                    const L = window.L;

                    const facilitiesRes = await fetch(
                        `https://firestore.googleapis.com/v1/projects/${FIREBASE_CONFIG.projectId}/databases/(default)/documents/facilities`
                    );
                    const facilitiesData = await facilitiesRes.json();

                    const boundariesRes = await fetch(
                        `https://firestore.googleapis.com/v1/projects/${FIREBASE_CONFIG.projectId}/databases/(default)/documents/boundaries/barangay_main`
                    );
                    const boundaryData = await boundariesRes.json();

                    const floodZonesRes = await fetch(
                        `https://firestore.googleapis.com/v1/projects/${FIREBASE_CONFIG.projectId}/databases/(default)/documents/flood_zones`
                    );
                    const floodZonesData = await floodZonesRes.json();

                    let facilityCount = 0;
                    let healthCount = 0;
                    let securityCount = 0;

                    if (facilitiesData.documents) {
                        facilitiesData.documents.forEach(doc => {
                            const fields = doc.fields;
                            const name = fields.name?.stringValue || 'Unknown';
                            const type = fields.type?.stringValue || 'general';
                            const lat = parseFloat(fields.location?.mapValue?.fields?.latitude?.doubleValue);
                            const lng = parseFloat(fields.location?.mapValue?.fields?.longitude?.doubleValue);

                            if (!lat || !lng) return;

                            const icon = L.divIcon({
                                html: getMarkerIcon(type),
                                className: 'custom-marker',
                                iconSize: [32, 32]
                            });

                            const marker = L.marker([lat, lng], { icon })
                                .bindPopup(`
                                    <div style="padding: 8px;">
                                        <h3 style="font-weight: bold; font-size: 14px; margin-bottom: 4px;">${name}</h3>
                                        <p style="font-size: 12px; color: #666;">${type}</p>
                                    </div>
                                `);

                            if (type === 'health_center') {
                                marker.addTo(layerGroups.current.health);
                                healthCount++;
                            } else if (type === 'security_post') {
                                marker.addTo(layerGroups.current.security);
                                securityCount++;
                            } else {
                                marker.addTo(layerGroups.current.facilities);
                                facilityCount++;
                            }
                        });
                    }

                    if (boundaryData.fields?.coordinates?.arrayValue?.values) {
                        const coords = boundaryData.fields.coordinates.arrayValue.values.map(coord => {
                            const lat = coord.mapValue.fields.latitude.doubleValue;
                            const lng = coord.mapValue.fields.longitude.doubleValue;
                            return [parseFloat(lat), parseFloat(lng)];
                        });

                        L.polygon(coords, {
                            color: '#ef4444',
                            weight: 3,
                            fillOpacity: 0.1
                        }).addTo(layerGroups.current.boundary);
                    }

                    if (floodZonesData.documents) {
                        floodZonesData.documents.forEach(doc => {
                            const fields = doc.fields;
                            const level = fields.level?.stringValue;
                            const coords = fields.coordinates?.arrayValue?.values?.map(coord => {
                                const lat = coord.mapValue.fields.latitude.doubleValue;
                                const lng = coord.mapValue.fields.longitude.doubleValue;
                                return [parseFloat(lat), parseFloat(lng)];
                            });

                            if (!coords) return;

                            const color = level === 'very_high' ? '#7c3aed' :
                                         level === 'high' ? '#6366f1' :
                                         '#93c5fd';

                            L.polygon(coords, {
                                color: color,
                                weight: 2,
                                fillOpacity: 0.3
                            }).addTo(layerGroups.current.floodZones);
                        });
                    }

                    setStats({
                        facilities: facilityCount,
                        health: healthCount,
                        security: securityCount,
                        emergencies: 0
                    });

                    setLoading(false);
                } catch (error) {
                    console.error('Error fetching map data:', error);
                    setLoading(false);
                }
            };

            fetchData();
        }, [map]);

        useEffect(() => {
            if (!map) return;

            Object.keys(activeLayers).forEach(key => {
                const layerGroup = layerGroups.current[key];
                if (layerGroup) {
                    if (activeLayers[key]) {
                        map.addLayer(layerGroup);
                    } else {
                        map.removeLayer(layerGroup);
                    }
                }
            });
        }, [activeLayers, map]);

        const getMarkerIcon = (type) => {
            const iconMap = {
                barangay_hall: '<div style="width:24px;height:24px;background:#3b82f6;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:16px;">🏛️</div>',
                health_center: '<div style="width:24px;height:24px;background:#ef4444;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:16px;">🏥</div>',
                security_post: '<div style="width:24px;height:24px;background:#10b981;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:16px;">🛡️</div>',
                default: '<div style="width:24px;height:24px;background:#6b7280;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-size:16px;">📍</div>'
            };
            return iconMap[type] || iconMap.default;
        };

        const toggleLayer = (layer) => {
            setActiveLayers(prev => ({
                ...prev,
                [layer]: !prev[layer]
            }));
        };

        return React.createElement('div', {
            style: { position: 'relative', width: '100%', height: '100%', backgroundColor: '#f3f4f6' }
        },
            React.createElement('div', { ref: mapRef, style: { width: '100%', height: '100%' } }),
            
            loading && React.createElement('div', {
                style: {
                    position: 'absolute',
                    inset: 0,
                    backgroundColor: 'rgba(0,0,0,0.5)',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    zIndex: 1000
                }
            },
                React.createElement('div', {
                    style: {
                        backgroundColor: 'white',
                        borderRadius: '8px',
                        padding: '24px',
                        textAlign: 'center'
                    }
                },
                    React.createElement('div', {
                        style: {
                            width: '48px',
                            height: '48px',
                            border: '4px solid #3b82f6',
                            borderTopColor: 'transparent',
                            borderRadius: '50%',
                            animation: 'spin 1s linear infinite',
                            margin: '0 auto'
                        }
                    }),
                    React.createElement('p', {
                        style: { marginTop: '16px', color: '#374151' }
                    }, 'Loading map data...')
                )
            ),
            
            React.createElement('div', {
                style: {
                    position: 'absolute',
                    top: '16px',
                    left: '16px',
                    zIndex: 1000,
                    backgroundColor: 'white',
                    borderRadius: '8px',
                    boxShadow: '0 4px 6px rgba(0,0,0,0.1)',
                    padding: '16px',
                    maxWidth: '320px'
                }
            },
                React.createElement('h3', {
                    style: { fontWeight: 'bold', color: '#1f2937', marginBottom: '12px' }
                }, '🗺️ Map Layers'),
                React.createElement('div', { style: { display: 'flex', flexDirection: 'column', gap: '8px' } },
                    ['facilities', 'health', 'security', 'floodZones', 'boundary'].map(layer => 
                        React.createElement('label', {
                            key: layer,
                            style: { display: 'flex', alignItems: 'center', gap: '8px', cursor: 'pointer' }
                        },
                            React.createElement('input', {
                                type: 'checkbox',
                                checked: activeLayers[layer],
                                onChange: () => toggleLayer(layer),
                                style: { width: '16px', height: '16px' }
                            }),
                            React.createElement('span', { style: { fontSize: '14px' } },
                                layer === 'facilities' ? `🏢 Facilities (${stats.facilities})` :
                                layer === 'health' ? `❤️ Health Centers (${stats.health})` :
                                layer === 'security' ? `🛡️ Security Posts (${stats.security})` :
                                layer === 'floodZones' ? '⚠️ Flood Zones' :
                                '📍 Barangay Boundary'
                            )
                        )
                    )
                ),
                React.createElement('button', {
                    onClick: () => setShowInfo(!showInfo),
                    style: {
                        marginTop: '16px',
                        width: '100%',
                        padding: '8px 16px',
                        backgroundColor: '#3b82f6',
                        color: 'white',
                        borderRadius: '8px',
                        border: 'none',
                        cursor: 'pointer',
                        fontSize: '14px',
                        fontWeight: '500'
                    }
                }, 'ℹ️ Emergency Info')
            ),
            
            showLegend && React.createElement('div', {
                style: {
                    position: 'absolute',
                    bottom: '16px',
                    left: '16px',
                    zIndex: 1000,
                    backgroundColor: 'white',
                    borderRadius: '8px',
                    boxShadow: '0 4px 6px rgba(0,0,0,0.1)',
                    padding: '16px',
                    maxWidth: '320px'
                }
            },
                React.createElement('div', {
                    style: { display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '12px' }
                },
                    React.createElement('h3', { style: { fontWeight: 'bold', color: '#1f2937' } }, 'Legend'),
                    React.createElement('button', {
                        onClick: () => setShowLegend(false),
                        style: { background: 'none', border: 'none', cursor: 'pointer', fontSize: '18px' }
                    }, '✕')
                ),
                React.createElement('div', { style: { display: 'flex', flexDirection: 'column', gap: '8px', fontSize: '14px' } },
                    [
                        { color: '#7c3aed', label: 'Very High Flood Risk' },
                        { color: '#6366f1', label: 'High Flood Risk' },
                        { color: '#93c5fd', label: 'Moderate Flood Risk' }
                    ].map((item, i) =>
                        React.createElement('div', {
                            key: i,
                            style: { display: 'flex', alignItems: 'center', gap: '8px' }
                        },
                            React.createElement('div', {
                                style: {
                                    width: '16px',
                                    height: '16px',
                                    backgroundColor: item.color,
                                    borderRadius: '4px',
                                    opacity: 0.5
                                }
                            }),
                            React.createElement('span', null, item.label)
                        )
                    )
                )
            ),
            
            showInfo && React.createElement('div', {
                style: {
                    position: 'absolute',
                    top: '50%',
                    left: '50%',
                    transform: 'translate(-50%, -50%)',
                    zIndex: 1001,
                    backgroundColor: 'white',
                    borderRadius: '8px',
                    boxShadow: '0 10px 25px rgba(0,0,0,0.2)',
                    padding: '24px',
                    maxWidth: '450px',
                    width: '90%'
                }
            },
                React.createElement('div', {
                    style: { display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '16px' }
                },
                    React.createElement('h3', {
                        style: { fontWeight: 'bold', color: '#1f2937', fontSize: '18px' }
                    }, 'Emergency Contacts'),
                    React.createElement('button', {
                        onClick: () => setShowInfo(false),
                        style: { background: 'none', border: 'none', cursor: 'pointer', fontSize: '20px' }
                    }, '✕')
                ),
                React.createElement('div', { style: { display: 'flex', flexDirection: 'column', gap: '12px' } },
                    React.createElement('div', {
                        style: { padding: '12px', backgroundColor: '#fef2f2', borderRadius: '8px' }
                    },
                        React.createElement('p', {
                            style: { fontWeight: '600', color: '#991b1b', fontSize: '14px' }
                        }, 'Emergency Hotline'),
                        React.createElement('p', {
                            style: { fontSize: '24px', fontWeight: 'bold', color: '#dc2626' }
                        }, '911')
                    ),
                    React.createElement('div', {
                        style: { padding: '12px', backgroundColor: '#eff6ff', borderRadius: '8px' }
                    },
                        React.createElement('p', {
                            style: { fontWeight: '600', color: '#1e40af', fontSize: '14px' }
                        }, 'Barangay Hall'),
                        React.createElement('p', {
                            style: { fontSize: '18px', fontWeight: 'bold', color: '#2563eb' }
                        }, '(043) XXX-XXXX')
                    )
                )
            )
        );
    };

    const root = ReactDOM.createRoot(document.getElementById('map-root'));
    root.render(React.createElement(BarangayMap));
</script>

<style>
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .custom-marker {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
    
    .leaflet-popup-content-wrapper {
        border-radius: 8px;
    }
</style>
@endsection