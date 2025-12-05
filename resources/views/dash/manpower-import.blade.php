@extends('layout.AdmLayout')
@section('content')
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>

<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
        
        <div class="p-6 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Import Manpower Data</h2>
                <p class="text-xs text-slate-500 mt-1">
                    @if(isset($previewData))
                        Review data before saving to database.
                    @else
                        Upload <span class="font-bold text-slate-700">Excel (.xlsx)</span> or <span class="font-bold text-slate-700">CSV</span> file.
                    @endif
                </p>
            </div>
            <div class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded">
                {{ \Carbon\Carbon::parse($month)->format('M Y') }}
            </div>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
        <div class="p-4 bg-red-50 border-b border-red-100 text-red-700 text-xs">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
        @endif
        @if(session('error'))
        <div class="p-4 bg-red-50 border-b border-red-100 text-red-700 text-xs font-bold flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ session('error') }}
        </div>
        @endif

        {{-- Processing Status --}}
        <div id="processing-msg" class="hidden p-4 bg-blue-50 border-b border-blue-100 text-blue-800 text-xs font-bold flex items-center gap-2">
            <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            Converting Excel file... Please wait.
        </div>

        @if(isset($previewData) && count($previewData) > 0)
            {{-- Preview State --}}
            <div class="p-6">
                <div class="mb-4 flex items-center justify-between">
                    <span class="text-sm font-bold text-slate-600 uppercase tracking-wide">Previewing {{ count($previewData) }} Records (Type: {{ strtoupper($importType) }})</span>
                    <span class="text-xs text-orange-500 font-bold bg-orange-50 px-2 py-1 rounded border border-orange-100">Review Carefully</span>
                </div>
                
                <div class="border rounded-lg overflow-hidden mb-6 max-h-96 overflow-y-auto relative">
                    <table class="w-full text-xs text-left relative">
                        <thead class="bg-slate-100 text-slate-600 font-bold uppercase sticky top-0 shadow-sm">
                            <tr>
                                <th class="p-3 border-b bg-slate-100">NRP</th>
                                <th class="p-3 border-b bg-slate-100">Name</th>
                                @if($importType === 'in')
                                    <th class="p-3 border-b bg-slate-100">Company</th>
                                    <th class="p-3 border-b text-right bg-slate-100">Manhours</th>
                                @else
                                    <th class="p-3 border-b bg-slate-100">Date Out</th>
                                    <th class="p-3 border-b bg-slate-100">Reason</th>
                                @endif
                                <th class="p-3 border-b text-center bg-slate-100">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($previewData as $row)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-3 font-mono text-slate-500">{{ $row['nrp'] ?? '-' }}</td>
                                <td class="p-3 font-bold text-slate-700">{{ $row['name'] }}</td>
                                @if($importType === 'in')
                                    <td class="p-3 text-slate-500">{{ $row['company'] }}</td>
                                    <td class="p-3 text-right font-mono">{{ $row['manhours'] }}</td>
                                @else
                                    <td class="p-3 text-red-600 font-bold">{{ $row['date_out'] }}</td>
                                    <td class="p-3">{{ $row['out_reason'] }}</td>
                                @endif
                                <td class="p-3 text-center">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold {{ $row['action'] == 'Update Status' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                        {{ $row['action'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <form action="{{ route('manpower.import.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="month" value="{{ $month }}">
                    <input type="hidden" name="redirect_to" value="recap">
                    <input type="hidden" name="confirmed_data" value="{{ json_encode($previewData) }}">
                    
                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('manpower.import', ['month' => $month]) }}" class="px-4 py-2 bg-white border border-slate-300 text-slate-600 rounded font-bold text-xs hover:bg-slate-50 transition">Back to Upload</a>
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded font-bold text-xs hover:bg-green-700 shadow-md transition transform active:scale-95">Confirm & Save to Database</button>
                    </div>
                </form>
            </div>

        @else
            {{-- Upload State --}}
            <form id="uploadForm" action="{{ route('manpower.import.preview') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" id="csvContentInput" name="csv_content">
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Select File</label>
                    <input type="file" id="fileInput" name="file" accept=".csv, .xlsx, .xls" required 
                           class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    
                    <div class="mt-3 p-3 bg-slate-50 rounded border border-slate-200 text-[10px] text-slate-500 space-y-1">
                        <p class="font-bold text-slate-600 mb-1">Supported Formats:</p>
                        <p>1. <strong>CSV</strong> (Recommended)</p>
                        <p>2. <strong>Excel (.xlsx, .xls)</strong> - Will be automatically converted.</p>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('manpower.recap', ['month' => $month]) }}" class="px-4 py-2 bg-white border border-slate-300 text-slate-600 rounded font-bold text-xs hover:bg-slate-50 transition">Cancel</a>
                    <button type="submit" id="submitBtn" class="px-6 py-2 bg-[#002d5b] text-white rounded font-bold text-xs hover:bg-blue-900 shadow-md transition transform active:scale-95 flex items-center gap-2">
                        <span>Preview Data</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('uploadForm');
        const fileInput = document.getElementById('fileInput');
        const csvContentInput = document.getElementById('csvContentInput');
        const processingMsg = document.getElementById('processing-msg');
        const submitBtn = document.getElementById('submitBtn');

        if(form) {
            form.addEventListener('submit', function(e) {
                const file = fileInput.files[0];
                if (!file) return;

                const fileName = file.name.toLowerCase();
                if (fileName.endsWith('.xlsx') || fileName.endsWith('.xls')) {
                    e.preventDefault(); // Stop normal submission
                    
                    // Show processing UI
                    processingMsg.classList.remove('hidden');
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    submitBtn.disabled = true;

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        try {
                            const data = new Uint8Array(e.target.result);
                            const workbook = XLSX.read(data, {type: 'array'});
                            
                            // Get first sheet
                            const firstSheetName = workbook.SheetNames[0];
                            const worksheet = workbook.Sheets[firstSheetName];
                            
                            // Convert to CSV
                            const csv = XLSX.utils.sheet_to_csv(worksheet);
                            
                            // Populate hidden input and submit
                            csvContentInput.value = csv;
                            
                            // Remove file input name so it's not sent as binary
                            fileInput.removeAttribute('name');
                            
                            form.submit();
                        } catch (err) {
                            alert("Error converting Excel file. Please try saving as CSV manually.");
                            processingMsg.classList.add('hidden');
                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                            submitBtn.disabled = false;
                        }
                    };
                    reader.readAsArrayBuffer(file);
                }
                // If CSV, let it submit normally
            });
        }
    });
</script>
@endsection