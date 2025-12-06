
  <h1>Barangay e-Serbisyo - Web Admin Panel</h1>
  <p>
    <strong>Web-based version of the Barangay e-Serbisyo mobile app.</strong><br>
    Connects to the same Firebase Firestore database used by the mobile application.
  </p>
</div>

<hr>

<h2>🛠 Tech Stack</h2>
<ul>
  <li><strong>Framework:</strong> Laravel 11 (PHP 8.2+)</li>
  <li><strong>Database:</strong> Firebase Firestore (via REST API - <code>mrshan0/firestore-php</code>)</li>
  <li><strong>Styling:</strong> Tailwind CSS (CDN)</li>
</ul>

<h2>📋 Prerequisites</h2>
<p>Before you start, make sure you have installed:</p>
<ol>
  <li><strong>PHP</strong> (Version 8.2 or higher)</li>
  <li><strong>Composer</strong></li>
  <li><strong>Git</strong></li>
</ol>

<hr>

<h2>🚀 Installation Guide for Teammates</h2>
<p>Follow these steps to set up the project on your local machine:</p>

<h3>1. Clone the Repository</h3>
<p>Open your terminal and run:</p>
<pre><code>git clone &lt;YOUR_GITHUB_REPO_URL&gt;
cd barangay-web</code></pre>

<h3>2. Install Dependencies</h3>
<p>Download the required PHP libraries (including the Firestore connector):</p>
<pre><code>composer install</code></pre>

<h3>3. Environment Setup</h3>
<p>Create your environment file by copying the example:</p>
<pre><code>cp .env.example .env</code></pre>
<p><em>(On Windows CMD, just manually copy and paste <code>.env.example</code> and rename it to <code>.env</code>)</em></p>

<h3>4. Configure Firebase Keys</h3>
<p>Open your new <code>.env</code> file in a text editor. Scroll to the bottom and fill in the Firebase credentials (ask the Project Lead for these keys or get them from Firebase Console > Project Settings > Web API):</p>
<pre><code>FIREBASE_PROJECT_ID=barangay-eservice-app
FIREBASE_API_KEY=AIzaSyD... (paste the full key here)</code></pre>

<h3>5. Generate Application Key</h3>
<pre><code>php artisan key:generate</code></pre>

<h3>6. Run the Server</h3>
<p>Start the local development server:</p>
<pre><code>php artisan serve</code></pre>
<p>Access the dashboard at: <a href="http://127.0.0.1:8000">http://127.0.0.1:8000</a></p>

<hr>

<h2>📂 Project Structure Note</h2>
<ul>
  <li><strong>Controllers:</strong> <code>app/Http/Controllers/AdminController.php</code> (Handles fetching data from Firebase)</li>
  <li><strong>Views:</strong> <code>resources/views/admin/residents.blade.php</code> (The UI for the dashboard)</li>
  <li><strong>Routes:</strong> <code>routes/web.php</code></li>
</ul>

<h2>⚠️ Troubleshooting</h2>

<p><strong>"Class not found" error:</strong></p>
<pre><code>composer dump-autoload</code></pre>

<p><strong>Data not showing / "Empty collection":</strong></p>
<p>Double-check your <code>FIREBASE_API_KEY</code> and <code>FIREBASE_PROJECT_ID</code> in the <code>.env</code> file. Ensure they match the Firebase Console exactly.</p>

<p><strong>"500 Server Error":</strong></p>
<p>Check <code>storage/logs/laravel.log</code> for details. Usually, this means the API key is invalid or the collection name in the controller (<code>users</code>) doesn't match the database.</p>
```
