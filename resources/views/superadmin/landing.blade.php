<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Barber System | Premium SaaS</title>
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Definición de colores globales (SaaS) */
        :root {
            --primary-light: 250 204 21; /* yellow-400 */
            --primary-main: 234 179 8;   /* yellow-500 */
            --primary-dark: 202 138 4;   /* yellow-600 */
        }

        /* Custom Animations */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        
        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .text-glow { text-shadow: 0 0 20px rgba(234, 179, 8, 0.5); }
        .box-glow { box-shadow: 0 0 30px rgba(234, 179, 8, 0.15); }
        .hover-box-glow:hover { box-shadow: 0 0 40px rgba(234, 179, 8, 0.4); }

        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .glass-card {
            background: linear-gradient(145deg, rgba(31, 41, 55, 0.6) 0%, rgba(17, 24, 39, 0.8) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        /* Shine effect on text */
        .text-shine {
            background: linear-gradient(90deg, #ca8a04, #fde047, #ca8a04);
            background-size: 200% auto;
            color: transparent;
            -webkit-background-clip: text;
            background-clip: text;
            animation: shine 3s linear infinite;
        }
        @keyframes shine {
            to { background-position: 200% center; }
        }
    </style>
</head>
<body class="antialiased bg-[#0a0a0a] text-gray-100 font-sans selection:bg-yellow-500 selection:text-gray-900 overflow-x-hidden">
    
    <!-- Dynamic Background -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
        <!-- Animated Blobs -->
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-yellow-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-blob"></div>
        <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-yellow-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-20%] left-[20%] w-[500px] h-[500px] bg-orange-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-10 animate-blob animation-delay-4000"></div>
        
        <!-- Dark overlay to ensure contrast -->
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#0a0a0a]/80 to-[#0a0a0a]"></div>
    </div>

    <div class="relative z-10 min-h-screen flex flex-col">
        
        <!-- Premium Navbar -->
        <nav class="sticky top-0 w-full z-50 glass-panel border-b border-white/5 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center shadow-[0_0_15px_rgba(234,179,8,0.4)]">
                            <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-black text-white tracking-widest leading-none">CODEX</span>
                            <span class="text-xs font-bold text-yellow-500 tracking-[0.3em] uppercase leading-none mt-1">SaaS</span>
                        </div>
                    </div>
                    <div>
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center font-bold text-gray-300 hover:text-white border border-gray-700/50 hover:border-yellow-500/50 rounded-full px-6 py-2.5 glass-card transition-all duration-300 hover-box-glow text-sm">
                                Ir a mi Panel
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="font-semibold text-white hover:text-yellow-400 transition-colors duration-300 text-sm mr-6">
                                Iniciar Sesión
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Hero Section -->
        <main class="flex-1 flex items-center justify-center py-20 px-4 md:px-6 relative">
            <div class="max-w-5xl w-full mx-auto text-center">
                
                <div class="inline-block mb-6">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold tracking-widest text-yellow-400 uppercase bg-yellow-400/10 border border-yellow-400/20 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-yellow-400 mr-2 animate-pulse"></span>
                        Codex Solutions Presenta
                    </span>
                </div>

                <h1 class="text-6xl md:text-8xl font-black text-white uppercase tracking-tighter drop-shadow-2xl mb-2">
                    Barber <span class="text-shine">System</span>
                </h1>
                
                <p class="mt-6 text-lg md:text-2xl text-white max-w-3xl mx-auto font-light leading-relaxed">
                    Digitaliza tu barbería, <strong class="text-yellow-400 font-bold">reduce las inasistencias a cero</strong> y ofrece a tus clientes una experiencia de reserva premium con <strong class="text-white font-bold">tu propia marca</strong>.
                </p>
                
                <!-- Action Buttons -->
                <div class="mt-14 flex flex-col sm:flex-row gap-6 justify-center items-stretch">
                    
                    <div class="flex flex-col gap-3 group">
                        <p class="text-[10px] font-bold text-white uppercase tracking-[0.2em] group-hover:text-yellow-400 transition-colors">Para Emprendedores</p>
                        <a href="https://wa.me/50247045968?text=Hola%20Codex%20Solutions,%20quiero%20conocer%20los%20costos%20de%20Barber%20System%20y%20el%20mes%20gratis%20del%20plan%20STUDIO." 
                           target="_blank"
                           class="relative inline-flex items-center justify-center px-8 py-5 text-base font-black text-gray-900 uppercase tracking-wide bg-yellow-500 hover:bg-yellow-400 rounded-2xl overflow-hidden group-hover:scale-105 transition-all duration-300 shadow-[0_0_30px_rgba(234,179,8,0.3)] hover:shadow-[0_0_50px_rgba(234,179,8,0.6)]">
                            <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                            <span class="relative flex items-center gap-2">
                                Contratar Sistema
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </span>
                        </a>
                    </div>

                    <div class="flex items-center justify-center px-4 hidden sm:flex">
                        <div class="h-16 w-px bg-gradient-to-b from-transparent via-gray-700 to-transparent"></div>
                    </div>

                    <div class="flex flex-col gap-3 group">
                        <p class="text-[10px] font-bold text-white uppercase tracking-[0.2em] group-hover:text-yellow-400 transition-colors">Para Socios Registrados</p>
                        <a href="{{ route('global.login') }}" 
                           class="relative inline-flex items-center justify-center px-8 py-5 text-base font-bold text-white uppercase tracking-wide glass-card rounded-2xl group-hover:scale-105 group-hover:border-yellow-500/50 transition-all duration-300 hover:shadow-[0_0_30px_rgba(234,179,8,0.15)]">
                            <span class="relative flex items-center gap-2">
                                Acceso Profesional
                                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </main>

        <!-- Features Section -->
        <section class="relative z-10 max-w-6xl mx-auto w-full pb-24 px-4 md:px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                            <!-- Feature 1 -->
                <div class="glass-card rounded-3xl p-8 flex flex-col items-center text-center transform hover:-translate-y-2 transition-all duration-300 hover:border-yellow-500/30 group">
                    <div class="w-16 h-16 rounded-2xl bg-gray-900 border border-gray-800 flex items-center justify-center mb-6 group-hover:shadow-[0_0_20px_rgba(234,179,8,0.2)] transition-shadow">
                        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Agenda Inteligente</h3>
                    <p class="text-gray-100 text-sm leading-relaxed">Nuestro algoritmo optimiza tu tiempo para evitar huecos. Gestiona múltiples barberos y maximiza los ingresos de cada silla.</p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-card rounded-3xl p-8 flex flex-col items-center text-center transform hover:-translate-y-2 transition-all duration-300 hover:border-yellow-500/30 group relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="w-16 h-16 rounded-2xl bg-gray-900 border border-gray-800 flex items-center justify-center mb-6 group-hover:shadow-[0_0_20px_rgba(234,179,8,0.2)] transition-shadow relative z-10">
                        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2 relative z-10">Anti No-Shows</h3>
                    <p class="text-gray-100 text-sm leading-relaxed relative z-10">Notificaciones y recordatorios automáticos para que tus clientes nunca olviden su cita. ¡Dile adiós a las sillas vacías!</p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card rounded-3xl p-8 flex flex-col items-center text-center transform hover:-translate-y-2 transition-all duration-300 hover:border-yellow-500/30 group">
                    <div class="w-16 h-16 rounded-2xl bg-gray-900 border border-gray-800 flex items-center justify-center mb-6 group-hover:shadow-[0_0_20px_rgba(234,179,8,0.2)] transition-shadow">
                        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Tu Propia App</h3>
                    <p class="text-gray-100 text-sm leading-relaxed">Tus clientes reservarán desde una aplicación rápida (PWA) instalable, que lleva 100% tus colores y tu logo, no el nuestro.</p>
                </div>     </div>

            </div>
        </section>

        <!-- Pricing Section -->
        <section class="relative z-10 max-w-7xl mx-auto w-full pb-32 px-4 md:px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-black text-white uppercase tracking-tight mb-4">Elige el plan para tu negocio</h2>
                <p class="text-gray-300 max-w-2xl mx-auto">Sin sorpresas ni contratos forzosos. Escala a medida que tu barbería crece.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                
                <!-- Plan Starter -->
                <div class="glass-card rounded-3xl p-8 border border-white/5 flex flex-col hover:border-yellow-500/20 transition-all duration-300">
                    <h3 class="text-xl font-bold text-gray-300 uppercase tracking-widest mb-2">Starter</h3>
                    <div class="text-sm text-gray-400 mb-6">Ideal para barberos independientes</div>
                    
                    <ul class="flex-1 space-y-4 mb-8 text-sm text-gray-200">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>1 Solo Barbero Independiente</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Agenda y reservas online</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Catálogo de servicios</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Panel de ingresos básico</span>
                        </li>
                    </ul>
                    
                    <a href="https://wa.me/50247045968?text=Hola,%20quisiera%20saber%20el%20costo%20del%20Plan%20STARTER." target="_blank" class="w-full py-4 rounded-xl border border-gray-700 text-white font-bold text-center hover:bg-gray-800 transition-colors uppercase text-sm tracking-wider">
                        Consultar Costo
                    </a>
                </div>

                <!-- Plan Studio (Destacado) -->
                <div class="glass-card rounded-3xl p-8 border-2 border-yellow-500 shadow-[0_0_30px_rgba(234,179,8,0.15)] relative transform md:-translate-y-4 flex flex-col z-10">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-yellow-400 to-yellow-600 text-gray-900 font-black px-4 py-1 rounded-full text-xs uppercase tracking-widest shadow-lg animate-pulse">
                        ⭐ 1 MES GRATIS
                    </div>
                    
                    <h3 class="text-2xl font-black text-white uppercase tracking-widest mb-2 mt-2">Studio</h3>
                    <div class="text-sm text-yellow-400 mb-6 font-semibold">El favorito de las barberías en crecimiento</div>
                    
                    <ul class="flex-1 space-y-4 mb-8 text-sm text-gray-200">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span><strong class="text-white">1 Solo Barbero</strong> (Nivel Premium)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-yellow-400 font-semibold">Todo lo de Starter, más:</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Módulo POS (Punto de Venta)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Recordatorios "Anti No-Shows"</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>App web PWA con tu marca</span>
                        </li>
                    </ul>
                    
                    <a href="https://wa.me/50247045968?text=Hola,%20quiero%20activar%20mi%20MES%20GRATIS%20del%20Plan%20STUDIO." target="_blank" class="w-full py-4 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-black text-center transition-colors uppercase text-sm tracking-wider shadow-lg">
                        Reclamar Mes Gratis
                    </a>
                </div>

                <!-- Plan Elite -->
                <div class="glass-card rounded-3xl p-8 border border-white/5 flex flex-col hover:border-yellow-500/20 transition-all duration-300">
                    <h3 class="text-xl font-bold text-gray-300 uppercase tracking-widest mb-2">Elite</h3>
                    <div class="text-sm text-gray-400 mb-6">Para barberías grandes y franquicias</div>
                    
                    <ul class="flex-1 space-y-4 mb-8 text-sm text-gray-200">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Gestión de <strong class="text-white">Múltiples Barberos</strong></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-yellow-400 font-semibold">Todo lo de Studio, más:</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Cálculo automático de comisiones</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Roles y accesos para tu equipo</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Reportes financieros globales</span>
                        </li>
                    </ul>
                    
                    <a href="https://wa.me/50200000000?text=Hola,%20me%20interesa%20el%20Plan%20ELITE%20para%20mi%20barbería." target="_blank" class="w-full py-4 rounded-xl border border-gray-700 text-white font-bold text-center hover:bg-gray-800 transition-colors uppercase text-sm tracking-wider">
                        Consultar Costo
                    </a>
                </div>

            </div>
        </section>

        <!-- Footer -->
        <footer class="relative z-10 w-full py-8 border-t border-white/5 glass-panel text-center mt-auto">
            <p class="text-gray-500 text-sm font-medium flex items-center justify-center gap-2">
                &copy; {{ date('Y') }} <span class="text-white font-bold tracking-wider">CODEX SOLUTIONS</span>
                <span class="w-1 h-1 rounded-full bg-gray-700"></span>
                Todos los derechos reservados.
            </p>
        </footer>
        
    </div>
</body>
</html>
