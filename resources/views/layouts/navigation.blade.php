<nav class="border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800" x-data="{ open: false }">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <!-- Logo -->
                <div class="flex shrink-0 items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <x-lahan-switcher />
                </div>

                <!-- Navigation Links -->

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :active="request()->routeIs('dashboard')" :href="route('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :active="request()->routeIs('lahans.*')" :href="route('lahans.index')">
                        {{ __('Lahan') }}
                    </x-nav-link>

                    <x-nav-link :active="request()->routeIs('transactions.*')" :href="route('transactions.index')">
                        {{ __('Transaksi') }}
                    </x-nav-link>
                    <x-nav-link :active="request()->routeIs('progress-logs.*')" :href="route('progress-logs.index')">
                        {{ __('Progress') }}
                    </x-nav-link>
                    <x-nav-link :active="request()->routeIs('trouble-reports.*')" :href="route('trouble-reports.index')">
                        {{ __('Trouble Report') }}
                    </x-nav-link>
                    <x-nav-link :active="request()->routeIs('partners.*')" :href="route('partners.index')">
                        {{ __('Partner') }}
                    </x-nav-link>
                    <x-nav-link :active="request()->routeIs('assets.*')" :href="route('assets.index')">
                        {{ __('Aset') }}
                    </x-nav-link>
                    <x-nav-link :active="request()->routeIs('schedules.*')" :href="route('schedules.index')">
                        {{ __('Jadwal') }}
                    </x-nav-link>
                    <x-nav-link :active="request()->routeIs('reports.*')" :href="route('reports.form')">
                        {{ __('Laporan') }}
                    </x-nav-link>
                    <x-nav-link :active="request()->routeIs('panen-cycles.*')" :href="route('panen-cycles.index')">
                        {{ __('Panen') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:ms-6 sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path clip-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        fill-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" d="M6 18L18 6M6 6l12 12"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">

        <div class="space-y-1 pb-3 pt-2">
            <x-responsive-nav-link :active="request()->routeIs('dashboard')" :href="route('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :active="request()->routeIs('lahans.*')" :href="route('lahans.index')">
                {{ __('Lahan') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :active="request()->routeIs('transactions.*')" :href="route('transactions.index')">
                {{ __('Transaksi') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :active="request()->routeIs('progress-logs.*')" :href="route('progress-logs.index')">
                {{ __('Progress') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :active="request()->routeIs('trouble-reports.*')" :href="route('trouble-reports.index')">
                {{ __('Trouble Report') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :active="request()->routeIs('partners.*')" :href="route('partners.index')">
                {{ __('Partner') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :active="request()->routeIs('assets.*')" :href="route('assets.index')">
                {{ __('Aset') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :active="request()->routeIs('schedules.*')" :href="route('schedules.index')">
                {{ __('Jadwal') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :active="request()->routeIs('reports.*')" :href="route('reports.form')">
                {{ __('Laporan') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :active="request()->routeIs('panen-cycles.*')" :href="route('panen-cycles.index')">
                {{ __('Panen') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600">
            <div class="px-4">
                <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
