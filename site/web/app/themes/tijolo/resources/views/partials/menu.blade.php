<div x-data="{ mobileOpen: false }" class="relative" x-cloak>
    <ul id="menu-menu-principal" class="hidden lg:flex text-lg justify-around font-heading relative items-center">
        @foreach ($primary_navigation as $item)
            {{-- Parent Menu Item Container --}}
            <li class="{{ $item->classes }} relative group transition-all duration-500 ease-in-out py-4">
                <a href="{{ $item->url }}"
                    class="px-3 py-2 rounded no-underline opacity-100 transition duration-300 inline-flex items-center hover:bg-tijolopinkhover {{ $item->active ? 'text-secondary font-semibold' : 'text-white' }}"
                    @if ($item->active || $item->activeAncestor) aria-current="{{ $item->active ? 'page' : 'true' }}" @endif>
                    {{ $item->label }}
                    
                    {{-- Pure Tailwind Arrow Indicator --}}
                    @if ($item->children)
                        <span class="ml-2 transition-transform duration-200 group-hover:rotate-180">▾</span>
                    @endif
                </a>

                {{-- Desktop Sub-menu Dropdown --}}
                @if ($item->children)
                    <ul class="absolute z-[99999] left-0 top-full mt-1 w-80 bg-tijolopinkhover shadow-md rounded p-2 transition-all duration-300 ease-out origin-top invisible opacity-0 -translate-y-2 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0">
                        @foreach ($item->children as $child)
                            <li class="{{ $child->classes }} w-full clear-both py-2 px-4 rounded transition duration-200 hover:bg-secondary/20">
                                <a href="{{ $child->url }}"
                                    class="block text-sm no-underline {{ $child->active ? 'text-secondary font-semibold' : 'text-white' }}"
                                    @if ($child->active || $child->activeAncestor) aria-current="{{ $child->active ? 'page' : 'true' }}" @endif>
                                    {{ $child->label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>

            {{-- WooCommerce Fast Cart Integration --}}
            @if (function_exists('wc_get_cart_url') && $item->url === wc_get_cart_url())
                <li class="menu-item fc-menu-item menu-item-type-fc py-4">
                    <a class="fc-cart-menu-item-link" href="{{ $item->url }}">
                        <span class="fc-menu-item-inner" data-count="{{ WC()->cart->get_cart_contents_count() }}">
                            <span class="fc-icon-shopping-basket"></span>
                            <span class="fc-menu-item-inner-subtotal">{!! WC()->cart->get_cart_subtotal() !!}</span>
                        </span>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>

    <button @click.stop="mobileOpen = !mobileOpen" class="lg:hidden p-2 relative w-10 h-10 z-50"
        :aria-expanded="mobileOpen" aria-label="Menu">
        <svg x-show="!mobileOpen" class="absolute inset-0 w-full h-full" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"
                color="var(--color-secondary)" />
        </svg>
        <svg x-show="mobileOpen" style="display: none" class="absolute inset-0 w-full h-full" fill="none" viewBox="0 0 24 24"
            stroke="var(--color-secondary)">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-offsecondary lg:hidden mt-28" style="display: none">
        <div class="container px-10 py-4 bg-white shadow-xl h-full overflow-y-auto">
            
            <ul id="menu-mobile" class="space-y-8">
                @foreach ($primary_navigation as $item)
                    {{-- Mobile Loop Scope --}}
                    <li class="{{ $item->classes }} relative flex flex-col"
                        @if($item->children) x-data="{ subMenuOpen: false }" @endif>
                        
                        <div class="flex items-center justify-between border-b border-gray-100 py-3">
                            <a href="{{ $item->url }}" @click="mobileOpen = false"
                                class="text-xl no-underline {{ $item->active ? 'text-secondary' : 'text-gray-800' }}"
                                @if ($item->active || $item->activeAncestor) aria-current="{{ $item->active ? 'page' : 'true' }}" @endif>
                                {{ $item->label }}
                            </a>

                            {{-- Mobile Dropdown Toggle Action Arrow --}}
                            @if ($item->children)
                                <button @click.prevent.stop="subMenuOpen = !subMenuOpen" 
                                    class="p-2 text-2xl text-gray-500 focus:outline-none transition-transform duration-200"
                                    :class="subMenuOpen ? 'rotate-180' : ''">
                                    ▾
                                </button>
                            @endif
                        </div>

                        {{-- Mobile Sub-menu Accordion Container --}}
                        @if ($item->children)
                            <ul class="pl-4 mt-2 space-y-2 bg-tijolopinkhover/10 rounded-md p-2" 
                                x-show="subMenuOpen" 
                                x-collapse 
                                style="display: none;">
                                @foreach ($item->children as $child)
                                    <li class="{{ $child->classes }} w-full clear-both py-2">
                                        <a href="{{ $child->url }}" @click="mobileOpen = false"
                                            class="block text-lg no-underline {{ $child->active ? 'text-secondary' : 'text-gray-600' }}">
                                            {{ $child->label }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>

                    {{-- Mobile WooCommerce Fast Cart Item --}}
                    @if (function_exists('wc_get_cart_url') && $item->url === wc_get_cart_url())
                        <li class="menu-item py-3 border-b border-gray-100">
                            <a href="{{ $item->url }}" @click="mobileOpen = false"
                                class="fc-cart-menu-item-link block text-xl {{ $item->active ? 'text-secondary' : 'text-gray-800' }}">
                                <span class="fc-menu-item-inner" data-count="{{ WC()->cart->get_cart_contents_count() }}">
                                    <span class="fc-icon-shopping-basket"></span>
                                    <span class="fc-menu-item-inner-subtotal">{!! WC()->cart->get_cart_subtotal() !!}</span>
                                </span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>