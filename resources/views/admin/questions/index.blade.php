<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800">Bank Soal</h2>
            <a href="{{ route('admin.questions.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                + Tambah Soal
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filters --}}
            <form method="GET" class="flex flex-wrap gap-3 mb-4">
                <select name="category_id" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}</option>
                    @endforeach
                </select>

                @if ($subtopics->isNotEmpty())
                    <select name="subtopic_id" onchange="this.form.submit()"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Subtopik</option>
                        @foreach ($subtopics as $sub)
                            <option value="{{ $sub->id }}"
                                {{ request('subtopic_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                        @endforeach
                    </select>
                @endif

                <select name="difficulty" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Tingkat</option>
                    <option value="1" {{ request('difficulty') == 1 ? 'selected' : '' }}>Mudah</option>
                    <option value="2" {{ request('difficulty') == 2 ? 'selected' : '' }}>Sedang</option>
                    <option value="3" {{ request('difficulty') == 3 ? 'selected' : '' }}>Sulit</option>
                </select>
            </form>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left w-1/2">Pertanyaan</th>
                            <th class="px-4 py-3 text-left">Subtopik</th>
                            <th class="px-4 py-3 text-center">Tingkat</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($questions as $q)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-800 max-w-sm truncate">
                                    {{ Str::limit($q->question_text, 80) }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $q->subtopic->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-xs
                                    {{ $q->difficulty == 1 ? 'bg-green-100 text-green-700' : ($q->difficulty == 2 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ ['1' => 'Mudah', '2' => 'Sedang', '3' => 'Sulit'][$q->difficulty] ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('admin.questions.destroy', $q) }}"
                                        onsubmit="return confirm('Hapus soal ini?')">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-gray-400">Belum ada soal.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $questions->links() }}</div>
        </div>
    </div>
</x-app-layout>
