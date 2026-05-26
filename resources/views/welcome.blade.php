{{-- # Simple Hybrid Dashboard for `resources/views/welcome.blade.php`

```blade --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Dashboard</title>

    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="max-w-7xl mx-auto p-6">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800">
                Ecommerce Backend Dashboard
            </h1>

            <p class="text-gray-500 mt-2">
                Admin + Vendor + API Architecture
            </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <div class="bg-white rounded-2xl shadow p-6 border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-600 mb-2">
                    Admin Panel
                </h2>

                <p class="text-3xl font-bold text-blue-600">
                    Blade
                </p>

                <p class="text-sm text-gray-500 mt-2">
                    Traditional Laravel Admin Dashboard
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow p-6 border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-600 mb-2">
                    Vendor Panel
                </h2>

                <p class="text-3xl font-bold text-green-600">
                    Vue + Inertia
                </p>

                <p class="text-sm text-gray-500 mt-2">
                    Modern SPA Vendor System
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow p-6 border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-600 mb-2">
                    Frontend API
                </h2>

                <p class="text-3xl font-bold text-purple-600">
                    REST API
                </p>

                <p class="text-sm text-gray-500 mt-2">
                    Mobile / React / External Frontend
                </p>
            </div>
        </div>

        <!-- Architecture -->
        <div class="bg-white rounded-2xl shadow border border-gray-200 p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">
                System Architecture
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <h3 class="text-xl font-semibold mb-3 text-blue-600">
                        Admin
                    </h3>

                    <ul class="space-y-2 text-gray-600">
                        <li>• Blade Templates</li>
                        <li>• Laravel Session Auth</li>
                        <li>• CRUD Operations</li>
                        <li>• Dashboard Analytics</li>
                    </ul>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <h3 class="text-xl font-semibold mb-3 text-green-600">
                        Vendor
                    </h3>

                    <ul class="space-y-2 text-gray-600">
                        <li>• Vue 3</li>
                        <li>• Inertia.js</li>
                        <li>• Tailwind CSS v4</li>
                        <li>• Vendor Product Management</li>
                    </ul>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <h3 class="text-xl font-semibold mb-3 text-purple-600">
                        API
                    </h3>

                    <ul class="space-y-2 text-gray-600">
                        <li>• Laravel API</li>
                        <li>• JSON Responses</li>
                        <li>• Authentication</li>
                        <li>• Mobile App Support</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-white rounded-2xl shadow border border-gray-200 p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">
                Quick Access
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <a href="/admin/dashboard"
                    class="bg-blue-600 hover:bg-blue-700 text-white p-5 rounded-xl transition duration-200 text-center font-semibold">
                    Open Admin Panel
                </a>

                <a href="/vendor"
                    class="bg-green-600 hover:bg-green-700 text-white p-5 rounded-xl transition duration-200 text-center font-semibold">
                    Open Vendor Panel
                </a>

                <a href="/api-list"
                    class="bg-purple-600 hover:bg-purple-700 text-white p-5 rounded-xl transition duration-200 text-center font-semibold">
                    API Routes
                </a>
            </div>
        </div>

    </div>

</body>

</html>
{{-- ```

## Route Example

```php
Route::get('/', function () {
return view('welcome');
});
```

## Final Structure

```text
Admin -> Blade
Vendor -> Vue + Inertia
Frontend -> API
Backend -> Laravel
``` --}}
