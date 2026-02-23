<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tryouts.index') }}" class="text-gray-500 hover:text-gray-700">← Kembali</a>
            <h2 class="font-semibold text-xl text-gray-800">Buat Tryout Baru</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-lg mx-auto px-4">
            <div class="bg-white rounded-xl shadow p-6">
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4 text-sm text-gray-500 bg-blue-50 border border-blue-100 rounded-lg px-4 py-2">
                    Tersedia <strong>{{ $questionCount }}</strong> soal di database.
                </div>

                <form method="POST" action="{{ route('admin.tryouts.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Tryout</label>
                        <input name="title" value="{{ old('title') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Durasi (menit)</label>
                        <input name="duration_minutes" type="number" min="10" max="300"
                            value="{{ old('duration_minutes', 60) }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Soal</label>
                        <input name="total_questions" type="number" min="1" max="{{ $questionCount }}"
                            value="{{ old('total_questions', 30) }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-400 mt-1">Soal akan dipilih secara acak dari database.</p>
                    </div>
                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-medium py-2.5 rounded-lg hover:bg-blue-700 transition">
                        Buat Tryout
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
