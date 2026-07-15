@extends('admin.layout')

@section('title', 'Collections')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Header Area -->
    <div class="scroll-reveal flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <div class="inline-flex items-center admin-badge bg-[#EAEAEA] text-[#111111]">Inventory</div>
            <h1 class="font-serif text-5xl md:text-6xl tracking-tight leading-none text-[#111111]">Collections</h1>
        </div>
        
        <div class="flex items-center gap-4 w-full md:w-auto">
            <button type="button" id="open-add-collection" class="flex items-center justify-center h-10 px-4 rounded-full bg-[#111111] text-white hover:bg-black transition-colors" title="Add Collection">
                <i class="ph-light ph-plus mr-2"></i> Add Collection
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="scroll-reveal p-4 bg-[#EDF3EC] text-[#346538] text-sm rounded-xl border border-[#EDF3EC]/50 flex items-center gap-3">
            <i class="ph-light ph-check-circle text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Data List -->
    <div class="scroll-reveal admin-outer-shell">
        <div class="admin-inner-core">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#EAEAEA] text-xs font-mono uppercase tracking-widest text-[#787774] bg-[#F9F9F8]">
                            <th class="px-6 py-4 font-normal">Collection</th>
                            <th class="px-6 py-4 font-normal hidden md:table-cell">Slug</th>
                            <th class="px-6 py-4 font-normal text-right">Products Count</th>
                            <th class="px-6 py-4 font-normal text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EAEAEA]">
                        @forelse($collections as $collection)
                            <tr class="group hover:bg-[#F9F9F8]/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-[#EAEAEA] overflow-hidden flex-shrink-0 flex items-center justify-center">
                                            @if($collection->image_url)
                                                <img src="{{ $collection->image_url }}" alt="{{ $collection->name }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="ph-light ph-image text-gray-400"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium text-[#111111] leading-tight">{{ $collection->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 hidden md:table-cell">
                                    <p class="font-mono text-xs text-[#787774]">{{ $collection->slug }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-mono text-sm text-[#111111]">{{ $collection->products_count ?? 0 }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.collections.edit', $collection->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full hover:bg-[#EAEAEA] hover:text-[#111111] transition-colors text-[#787774]">
                                        <i class="ph-light ph-pencil-simple text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <i class="ph-light ph-folders text-4xl text-[#EAEAEA] mb-2"></i>
                                    <p class="text-sm text-[#787774]">No collections found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($collections->hasPages())
            <div class="border-t border-[#EAEAEA] p-4 bg-white">
                {{ $collections->links('pagination::tailwind') }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Collection Modal -->
<div id="add-collection-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#111111]/40 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] flex flex-col shadow-2xl overflow-hidden m-4">
        <!-- Header -->
        <div class="p-6 border-b border-[#EAEAEA] flex justify-between items-center bg-[#F9F9F8]">
            <h2 class="font-serif text-2xl text-[#111111]">Add New Collection</h2>
            <button type="button" id="close-add-collection" class="text-[#787774] hover:text-[#111111]">
                <i class="ph-light ph-x text-2xl"></i>
            </button>
        </div>
        
        <!-- Body -->
        <form action="{{ route('admin.collections.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden max-h-full">
            @csrf
            <div class="p-8 overflow-y-auto space-y-6">
                @if($errors->any())
                    <div class="p-4 bg-[#FDEBEC] text-[#9F2F2D] text-sm rounded-xl border border-[#FDEBEC]/50 flex flex-col gap-1">
                        @foreach($errors->all() as $error)
                            <div class="flex items-center gap-2"><i class="ph-light ph-warning-circle"></i> {{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                
                <!-- Name -->
                <div>
                    <label class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Collection Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm text-[#111111] focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow">
                </div>

                <!-- Image -->
                <div>
                    <label class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Collection Image</label>
                    <input type="file" name="image_url" accept="image/*" class="w-full px-4 py-2 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm text-[#111111] focus:outline-none focus:ring-1 focus:ring-[#111111] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-[#111111] file:text-white hover:file:bg-[#333333] transition-all">
                </div>
                
                <!-- Description -->
                <div>
                    <label class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow">{{ old('description') }}</textarea>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="p-6 border-t border-[#EAEAEA] bg-[#F9F9F8] flex justify-end gap-4 shrink-0">
                <button type="button" id="close-add-collection-btn" class="px-6 py-2.5 rounded-lg text-sm font-medium text-[#787774] hover:text-[#111111] transition-colors">
                    Cancel
                </button>
                <button type="submit" class="admin-button-island group bg-[#111111] text-white hover:bg-[#333333] transition-haptic active:scale-95">
                    <span>Create Collection</span>
                    <div class="admin-button-island-icon bg-white/10 group-hover:bg-white/20 transition-colors">
                        <i class="ph-light ph-plus text-white"></i>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('add-collection-modal');
        const openBtn = document.getElementById('open-add-collection');
        const closeBtns = [document.getElementById('close-add-collection'), document.getElementById('close-add-collection-btn')];
        const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
        
        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        if(openBtn) {
            openBtn.addEventListener('click', (e) => {
                e.preventDefault();
                openModal();
            });
        }
        
        closeBtns.forEach(btn => {
            if(btn) {
                btn.addEventListener('click', closeModal);
            }
        });

        if (hasErrors) {
            openModal();
        }
    });
</script>
@endsection
