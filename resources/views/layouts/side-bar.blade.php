 <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
     class="fixed left-0 top-0 z-40 flex h-screen w-64 flex-col border-r border-line bg-surface transition-transform duration-200 lg:sticky">

     <div class="border-b border-line px-5 py-5">
         <a class="flex items-center gap-2" href="{{ route('dashboard') }}">
             <div
                 class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-sm font-semibold text-white">
                 P</div>
             <span class="font-semibold text-ink">Tani Pisang</span>
         </a>
     </div>

     <div class="border-b border-line px-4 py-4">
         <x-lahan-switcher />
     </div>

     <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-4">
         <div>
             <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wide text-ink-muted">Operasional</p>
             <div class="space-y-0.5">
                 <x-sidebar-link :active="request()->routeIs('dashboard')" :href="route('dashboard')">
                     <x-slot name="icon"><svg class="w-4.5 h-4.5" fill="none" stroke-width="1.8"
                             stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                             <path
                                 d="M3.75 12l8.25-8.25L20.25 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"
                                 stroke-linecap="round" stroke-linejoin="round" />
                         </svg></x-slot>
                     Dashboard
                 </x-sidebar-link>
                 @if (auth()->user()->role === 'admin')
                     <x-sidebar-link :active="request()->routeIs('transactions.*')" :href="route('transactions.index')">
                         <x-slot name="icon"><svg class="w-4.5 h-4.5" fill="none" stroke-width="1.8"
                                 stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                 <path
                                     d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                     stroke-linecap="round" stroke-linejoin="round" />
                             </svg></x-slot>
                         Transaksi
                     </x-sidebar-link>
                 @endif
                 <x-sidebar-link :active="request()->routeIs('progress-logs.*')" :href="route('progress-logs.index')">
                     <x-slot name="icon"><svg class="w-4.5 h-4.5" fill="none" stroke-width="1.8"
                             stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                             <path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                 stroke-linecap="round" stroke-linejoin="round" />
                         </svg></x-slot>
                     Progress
                 </x-sidebar-link>
                 <x-sidebar-link :active="request()->routeIs('trouble-reports.*')" :href="route('trouble-reports.index')">
                     <x-slot name="icon"><svg class="w-4.5 h-4.5" fill="none" stroke-width="1.8"
                             stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                             <path
                                 d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                                 stroke-linecap="round" stroke-linejoin="round" />
                         </svg></x-slot>
                     Trouble Report
                 </x-sidebar-link>
                 @if (auth()->user()->role === 'admin')
                     <x-sidebar-link :active="request()->routeIs('panen-cycles.*')" :href="route('panen-cycles.index')">
                         <x-slot name="icon"><svg class="w-4.5 h-4.5" fill="none" stroke-width="1.8"
                                 stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                 <path
                                     d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                                     stroke-linecap="round" stroke-linejoin="round" />
                             </svg></x-slot>
                         Panen
                     </x-sidebar-link>
                     <x-sidebar-link :active="request()->routeIs('schedules.*')" :href="route('schedules.index')">
                         <x-slot name="icon"><svg class="w-4.5 h-4.5" fill="none" stroke-width="1.8"
                                 stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                 <path
                                     d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"
                                     stroke-linecap="round" stroke-linejoin="round" />
                             </svg></x-slot>
                         Jadwal
                     </x-sidebar-link>
                 @endif
             </div>
         </div>

         @if (auth()->user()->role === 'admin')
             <div>
                 <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wide text-ink-muted">Lintas-Lahan</p>
                 <div class="space-y-0.5">
                     <x-sidebar-link :active="request()->routeIs('lahans.*')" :href="route('lahans.index')">
                         <x-slot name="icon"><svg class="w-4.5 h-4.5" fill="none" stroke-width="1.8"
                                 stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                 <path
                                     d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"
                                     stroke-linecap="round" stroke-linejoin="round" />
                             </svg></x-slot>
                         Lahan
                     </x-sidebar-link>
                     <x-sidebar-link :active="request()->routeIs('partners.*')" :href="route('partners.index')">
                         <x-slot name="icon"><svg class="w-4.5 h-4.5" fill="none" stroke-width="1.8"
                                 stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                 <path
                                     d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"
                                     stroke-linecap="round" stroke-linejoin="round" />
                             </svg></x-slot>
                         Partner
                     </x-sidebar-link>
                     <x-sidebar-link :active="request()->routeIs('assets.*')" :href="route('assets.index')">
                         <x-slot name="icon"><svg class="w-4.5 h-4.5" fill="none" stroke-width="1.8"
                                 stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                 <path
                                     d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26"
                                     stroke-linecap="round" stroke-linejoin="round" />
                             </svg></x-slot>
                         Aset
                     </x-sidebar-link>
                     <x-sidebar-link :active="request()->routeIs('reports.*')" :href="route('reports.form')">
                         <x-slot name="icon"><svg class="w-4.5 h-4.5" fill="none" stroke-width="1.8"
                                 stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                 <path
                                     d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-1.519-2.639L12 17.25m3.75-4.5v9M8.25 21h7.5a2.25 2.25 0 002.25-2.25V9.75l-6-6H6a2.25 2.25 0 00-2.25 2.25v12.75c0 1.243 1.007 2.25 2.25 2.25z"
                                     stroke-linecap="round" stroke-linejoin="round" />
                             </svg></x-slot>
                         Export Laporan
                     </x-sidebar-link>
                 </div>
             </div>
         @endif
     </nav>

     <div class="relative border-t border-line px-3 py-3" x-data="{ profileOpen: false }">
         <button @click="profileOpen = !profileOpen"
             class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left hover:bg-primary-tint">
             <div
                 class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-primary-tint text-xs font-semibold text-primary">
                 {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
             </div>
             <div class="min-w-0">
                 <p class="truncate text-sm font-medium text-ink">{{ auth()->user()->name }}</p>
                 <p class="text-xs text-ink-muted">{{ ucfirst(auth()->user()->role) }}</p>
             </div>
         </button>

         <div @click.away="profileOpen = false"
             class="absolute bottom-full left-3 right-3 z-50 mb-2 rounded-lg border border-line bg-surface py-1 shadow-lg"
             x-cloak x-show="profileOpen">
             <a class="block px-4 py-2 text-sm text-ink hover:bg-primary-tint"
                 href="{{ route('profile.edit') }}">Profil</a>
             <form action="{{ route('logout') }}" method="POST">
                 @csrf
                 <button class="w-full px-4 py-2 text-left text-sm text-ink hover:bg-primary-tint" type="submit">
                     Log Out
                 </button>
             </form>
         </div>
     </div>
 </aside>
