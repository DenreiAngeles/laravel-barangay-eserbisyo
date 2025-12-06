<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay e-Serbisyo Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Resident Masterlist</h1>
            <span class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                Total: {{ count($users) }}
            </span>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Resident
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Contact Info
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Address
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10">
                                    <img class="w-full h-full rounded-full border border-gray-300 object-cover"
                                         src="{{ $user['data']['profilePictureUrl'] ?? $user['data']['selfiePhotoUrl'] ?? 'https://ui-avatars.com/api/?name='.$user['data']['firstName'] }}"
                                         alt="" />
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-900 whitespace-no-wrap font-bold">
                                        {{ $user['data']['firstName'] ?? '' }} {{ $user['data']['lastName'] ?? '' }}
                                    </p>
                                    <p class="text-gray-600 whitespace-no-wrap text-xs">
                                        {{ $user['data']['civilStatus'] ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ $user['data']['email'] ?? 'No Email' }}</p>
                            <p class="text-gray-600 whitespace-no-wrap">{{ $user['data']['phoneNumber'] ?? 'No Phone' }}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ $user['data']['address'] ?? 'Unknown' }}
                            </p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            @php
                                $status = $user['data']['verificationStatus'] ?? 'pending';
                                $color = $status === 'verified' ? 'green' : 'orange';
                            @endphp
                            <span class="relative inline-block px-3 py-1 font-semibold text-{{$color}}-900 leading-tight">
                                <span aria-hidden="true" class="absolute inset-0 bg-{{$color}}-200 opacity-50 rounded-full"></span>
                                <span class="relative capitalize">{{ $status }}</span>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>