<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Together App - Admin & API Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#FF523B',
                        brandLight: '#FFF0ED',
                        dark: '#1E232A'
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">
    <!-- Navbar -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-brand text-white flex items-center justify-center font-black text-xl shadow-lg shadow-orange-500/20">
                    T
                </div>
                <div>
                    <span class="text-xl font-extrabold text-gray-900 tracking-tight">together</span>
                    <span class="ml-2 text-xs font-semibold px-2.5 py-0.5 rounded-full bg-orange-100 text-brand">Admin & API Dashboard</span>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                    <span class="w-2 h-2 mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    API Active (Port 8000)
                </span>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 rounded-3xl p-8 mb-8 text-white relative overflow-hidden shadow-2xl">
            <div class="relative z-10 max-w-2xl">
                <h1 class="text-3xl font-extrabold tracking-tight mb-2">Together App Master Control</h1>
                <p class="text-gray-300 text-sm leading-relaxed mb-6">
                    Backend service RESTful API untuk aplikasi mobile Together (Real People, Real Moments). Kelola basis data pengguna, event/momen komunitas, serta pesan secara real-time.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="/api/events" target="_blank" class="px-4 py-2 bg-brand text-white text-xs font-bold rounded-xl shadow-md hover:bg-orange-600 transition">
                        Lihat API Events &rarr;
                    </a>
                    <a href="/api/users" target="_blank" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl transition">
                        Lihat API Users &rarr;
                    </a>
                    <a href="/api/conversations" target="_blank" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-bold rounded-xl transition">
                        Lihat API Chats &rarr;
                    </a>
                </div>
            </div>
            <div class="absolute right-0 bottom-0 opacity-10 pointer-events-none transform translate-x-10 translate-y-10">
                <svg width="400" height="400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pengguna</div>
                <div class="text-3xl font-black text-gray-900">{{ $totalUsers }}</div>
                <div class="text-xs text-emerald-600 font-semibold mt-2 flex items-center">
                    <span>✓ Terkoneksi ke together_db</span>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Momen / Events</div>
                <div class="text-3xl font-black text-brand">{{ $totalEvents }}</div>
                <div class="text-xs text-gray-500 font-medium mt-2">Upcoming & Aktif</div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Percakapan / Chat</div>
                <div class="text-3xl font-black text-blue-600">{{ $totalConversations }}</div>
                <div class="text-xs text-gray-500 font-medium mt-2">Direct & Group Chat</div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pesan Terkirim</div>
                <div class="text-3xl font-black text-indigo-600">{{ $totalMessages }}</div>
                <div class="text-xs text-gray-500 font-medium mt-2">Real-time Messaging</div>
            </div>
        </div>

        <!-- Content Tables Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Events List -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-extrabold text-gray-900">Daftar Momen Komunitas (Events)</h2>
                    <span class="text-xs font-semibold px-2 py-1 bg-gray-100 text-gray-600 rounded-lg">{{ count($events) }} Terbaru</span>
                </div>
                <div class="space-y-4">
                    @forelse($events as $event)
                        <div class="flex items-center p-3 rounded-xl hover:bg-gray-50 transition border border-gray-50">
                            <img src="{{ $event->image_url ?? 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=200' }}" class="w-14 h-14 rounded-xl object-cover mr-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-bold text-brand uppercase">{{ $event->category }}</span>
                                    <span class="text-xs text-gray-400">• {{ $event->city }}</span>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900 truncate">{{ $event->title }}</h3>
                                <p class="text-xs text-gray-500 truncate">{{ $event->location_name }}</p>
                            </div>
                            <div class="text-right ml-4">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-orange-50 text-brand">
                                    {{ $event->participants_count ?? $event->participants->count() }} Going
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-400 text-xs">Belum ada event tersedia.</div>
                    @endforelse
                </div>
            </div>

            <!-- Users List -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-extrabold text-gray-900">Daftar Anggota Komunitas</h2>
                    <span class="text-xs font-semibold px-2 py-1 bg-gray-100 text-gray-600 rounded-lg">{{ count($users) }} Terdaftar</span>
                </div>
                <div class="space-y-3">
                    @forelse($users as $user)
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition border border-gray-50">
                            <div class="flex items-center space-x-3 min-w-0">
                                <img src="{{ $user->avatar_url ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150' }}" class="w-10 h-10 rounded-full object-cover">
                                <div class="truncate">
                                    <div class="text-sm font-bold text-gray-900 truncate">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500 truncate">{{ $user->email }} • {{ $user->city ?? 'Malang' }}</div>
                                </div>
                            </div>
                            <span class="text-xs px-2.5 py-1 font-semibold rounded-lg bg-gray-100 text-gray-700">
                                {{ $user->age ? $user->age . ' th' : 'User' }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-400 text-xs">Belum ada user.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Documentation Card -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-lg font-extrabold text-gray-900 mb-2">Endpoint REST API Documentation</h2>
            <p class="text-xs text-gray-500 mb-4">Endpoint ini dikonsumsi langsung oleh aplikasi Flutter Together Mobile:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-xs">
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="font-bold text-emerald-600">POST</span> <span class="font-mono text-gray-800">/api/register</span>
                    <p class="text-gray-400 mt-1">Registrasi pengguna baru</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="font-bold text-emerald-600">POST</span> <span class="font-mono text-gray-800">/api/login</span>
                    <p class="text-gray-400 mt-1">Login & peroleh token Sanctum</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="font-bold text-blue-600">GET</span> <span class="font-mono text-gray-800">/api/events/upcoming</span>
                    <p class="text-gray-400 mt-1">Home feed upcoming moments</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="font-bold text-blue-600">GET</span> <span class="font-mono text-gray-800">/api/users/nearby</span>
                    <p class="text-gray-400 mt-1">Daftar people near you</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="font-bold text-emerald-600">POST</span> <span class="font-mono text-gray-800">/api/events/{id}/join</span>
                    <p class="text-gray-400 mt-1">CTA "I'm In" join/leave event</p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="font-bold text-blue-600">GET</span> <span class="font-mono text-gray-800">/api/conversations</span>
                    <p class="text-gray-400 mt-1">Daftar obrolan personal & grup</p>
                </div>
            </div>
        </div>
    </main>

    <footer class="mt-12 py-6 border-t border-gray-200 text-center text-xs text-gray-500">
        &copy; 2026 Together App — Praktikum Pemrograman Aplikasi
    </footer>
</body>
</html>
