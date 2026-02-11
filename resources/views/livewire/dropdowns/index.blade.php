<div x-data="{ openModal: false }">
    <!-- Header -->
    <header class="sticky top-0 z-40 bg-white/70 backdrop-blur-xl border-b border-slate-100 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <a href="{{ route('home.index') }}" wire:navigate
                class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-slate-200 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-extrabold text-slate-800 leading-none">Dropdowns Management</h1>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Manajemen Dropdown</p>
            </div>
        </div>
        <button data-prevent-double wire:click="create" @click="openModal = true"
            class="px-5 py-2.5 bg-app-gradient text-white font-bold rounded-xl hover:shadow-lg hover:shadow-slate-900/20 active:scale-95 transition-all duration-300">
            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Dropdown
        </button>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-6 py-8 pb-40">
        <div class="glass-card rounded-[2rem] overflow-hidden shadow-xl shadow-slate-200">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-wider">
                                Category
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-wider">
                                Name Value
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-wider">
                                Code Format
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($items as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-slate-800">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $item->name_value }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    <code class="px-2 py-1 bg-slate-100 rounded text-xs font-mono">
                                        {{ $item->code_format ?: '-' }}
                                    </code>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button data-prevent-double
                                            wire:click="edit({{ $item->id }})"
                                            @click="openModal = true"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-600 hover:text-white transition-all">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                            Edit
                                        </button>
                                        <button data-prevent-double
                                            @click="$dispatch('confirm', { id: {{ $item->id }}, message: 'Delete this dropdown?' })"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-red-600 bg-red-50 hover:bg-red-600 hover:text-white transition-all">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                        <p class="text-sm font-bold">No dropdowns found</p>
                                        <p class="text-xs mt-1">Click "Add Dropdown" to create your first dropdown</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- MODAL --}}
    <div x-cloak
         x-show="openModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">

        <div x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="openModal = false"
             class="w-full max-w-lg glass-card rounded-[2rem] p-8 shadow-2xl">

            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-extrabold text-slate-800">
                    {{ $editingId ? 'Edit Dropdown' : 'Add New Dropdown' }}
                </h3>
                <button @click="openModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                        Category
                    </label>
                    <input type="text"
                           wire:model="category"
                           placeholder="Enter category"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-slate-100 focus:border-slate-400 transition-all outline-none font-medium text-sm">
                    @error('category')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                        Name Value
                    </label>
                    <input type="text"
                           wire:model="name_value"
                           placeholder="Enter name value"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-slate-100 focus:border-slate-400 transition-all outline-none font-medium text-sm">
                    @error('name_value')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                        Code Format <span class="text-slate-400 normal-case">(optional)</span>
                    </label>
                    <input type="text"
                           wire:model="code_format"
                           placeholder="e.g., CODE-{id}"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-slate-100 focus:border-slate-400 transition-all outline-none font-medium text-sm font-mono">
                    @error('code_format')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-8">
                <button @click="openModal = false"
                        class="px-5 py-2.5 rounded-xl border-2 border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">
                    Cancel
                </button>
                <button data-prevent-double
                        wire:click="save"
                        @click="openModal = false"
                        class="px-5 py-2.5 bg-app-gradient text-white font-bold rounded-xl hover:shadow-lg hover:shadow-slate-900/20 active:scale-95 transition-all duration-300">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Save Dropdown
                </button>
            </div>
        </div>
    </div>
</div>
