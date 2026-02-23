<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.questions.index') }}" class="text-gray-500 hover:text-gray-700">← Kembali</a>
            <h2 class="font-semibold text-xl text-gray-800">Tambah Soal Baru</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4">
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

                <form method="POST" action="{{ route('admin.questions.store') }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select id="cat-select" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subtopik</label>
                            <select name="subtopic_id" id="sub-select" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Subtopik</option>
                                @foreach ($categories as $cat)
                                    @foreach ($cat->subtopics as $sub)
                                        <option value="{{ $sub->id }}" data-cat="{{ $cat->id }}">
                                            {{ $sub->name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teks Pertanyaan</label>
                        <textarea name="question_text" rows="4" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('question_text') }}</textarea>
                    </div>

                    @foreach (['a', 'b', 'c', 'd', 'e'] as $opt)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Opsi
                                {{ strtoupper($opt) }}</label>
                            <input name="option_{{ $opt }}" value="{{ old('option_' . $opt) }}" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    @endforeach

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jawaban Benar</label>
                            <select name="correct_answer" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @foreach (['A', 'B', 'C', 'D', 'E'] as $v)
                                    <option value="{{ $v }}"
                                        {{ old('correct_answer') === $v ? 'selected' : '' }}>{{ $v }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Kesulitan</label>
                            <select name="difficulty" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="1" {{ old('difficulty') == 1 ? 'selected' : '' }}>Mudah</option>
                                <option value="2" {{ old('difficulty') == 2 ? 'selected' : '' }}>Sedang</option>
                                <option value="3" {{ old('difficulty') == 3 ? 'selected' : '' }}>Sulit</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-medium py-2.5 rounded-lg hover:bg-blue-700 transition">
                        Simpan Soal
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const catSelect = document.getElementById('cat-select');
        const subSelect = document.getElementById('sub-select');
        const allOpts = Array.from(subSelect.options);

        catSelect.addEventListener('change', function() {
            const catId = this.value;
            subSelect.innerHTML = '<option value="">Pilih Subtopik</option>';
            allOpts.filter(o => o.dataset.cat === catId || !o.value)
                .forEach(o => subSelect.add(o.cloneNode(true)));
        });
    </script>
</x-app-layout>
