@extends('admin.layout')

@section('title', 'Edit Collection')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Header -->
    <div class="scroll-reveal">
        <a href="{{ route('admin.collections.index') }}" class="inline-flex items-center text-sm font-medium text-[#787774] hover:text-[#111111] transition-colors mb-6">
            <i class="ph-light ph-arrow-left mr-2"></i> Back to Collections
        </a>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-2">
                <h1 class="font-serif text-5xl tracking-tight leading-none text-[#111111]">Edit Collection</h1>
                <p class="text-[#787774]">{{ $collection->name }}</p>
            </div>
            
            <div class="flex items-center gap-4">
                <form action="{{ route('admin.collections.destroy', $collection->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this collection? Products in this collection will not be deleted, but they will be removed from this collection.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center justify-center h-10 px-4 rounded-full border border-[#EAEAEA] bg-white text-[#9F2F2D] hover:bg-[#FDEBEC] hover:border-[#FDEBEC] transition-colors">
                        <i class="ph-light ph-trash mr-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="scroll-reveal p-4 bg-[#EDF3EC] text-[#346538] text-sm rounded-xl border border-[#EDF3EC]/50 flex items-center gap-3">
            <i class="ph-light ph-check-circle text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="scroll-reveal p-4 bg-[#FDEBEC] text-[#9F2F2D] text-sm rounded-xl border border-[#FDEBEC]/50 flex flex-col gap-1">
            @foreach($errors->all() as $error)
                <div class="flex items-center gap-2"><i class="ph-light ph-warning-circle"></i> {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="scroll-reveal max-w-3xl">
        <form action="{{ route('admin.collections.update', $collection->id) }}" method="POST" enctype="multipart/form-data" class="admin-outer-shell p-8 space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-2 space-y-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Collection Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $collection->name) }}" required class="w-full px-4 py-3 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm text-[#111111] focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Description</label>
                        <textarea name="description" rows="5" class="w-full px-4 py-3 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#111111] transition-shadow">{{ old('description', $collection->description) }}</textarea>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Image -->
                    <div>
                        <label class="block text-sm font-mono uppercase tracking-widest text-[#787774] mb-2">Collection Image</label>
                        @if($collection->image_url)
                            <div class="mb-4 rounded-xl border border-[#EAEAEA] overflow-hidden aspect-square bg-[#F9F9F8]">
                                <img src="{{ $collection->image_url }}" alt="{{ $collection->name }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <input type="file" name="image_url" accept="image/*" class="w-full px-4 py-2 bg-[#F9F9F8] border border-[#EAEAEA] rounded-lg text-sm text-[#111111] focus:outline-none focus:ring-1 focus:ring-[#111111] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-[#111111] file:text-white hover:file:bg-[#333333] transition-all">
                        <p class="text-xs text-[#787774] mt-2">Upload a new image to replace the current one.</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="pt-6 border-t border-[#EAEAEA] flex justify-end">
                <button type="submit" class="admin-button-island group bg-[#111111] text-white hover:bg-[#333333] transition-haptic active:scale-95">
                    <span>Save Changes</span>
                    <div class="admin-button-island-icon bg-white/10 group-hover:bg-white/20 transition-colors">
                        <i class="ph-light ph-check text-white"></i>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
