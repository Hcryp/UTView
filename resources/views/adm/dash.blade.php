<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard UT Satui - Oct 2025</title>
    <style>
        :root { --ut-yellow: #fcb900; --ut-dark: #2d2d2d; --bg: #f4f6f8; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); margin: 0; padding-bottom: 40px; }
        
        /* Navbar */
        .nav { background: var(--ut-yellow); padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .nav h1 { margin: 0; font-size: 1.25rem; color: var(--ut-dark); font-weight: 700; }
        .nav form button { background: var(--ut-dark); color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; }

        /* Container */
        .box { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }

        /* Cards */
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-left: 4px solid var(--ut-yellow); }
        .card h3 { margin: 0 0 0.5rem 0; color: #6b7280; font-size: 0.875rem; text-transform: uppercase; }
        .card .val { font-size: 2rem; font-weight: bold; color: var(--ut-dark); }
        .card .sub { font-size: 0.8rem; color: #9ca3af; }

        /* Section Header */
        .sec-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .sec-head h2 { font-size: 1.25rem; color: #1f2937; margin: 0; border-bottom: 2px solid var(--ut-yellow); display: inline-block; padding-bottom: 4px; }

        /* Table */
        .tbl-wrap { background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th { background: #f9fafb; text-align: left; padding: 0.75rem 1rem; color: #4b5563; font-weight: 600; border-bottom: 1px solid #e5e7eb; }
        td { padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; color: #1f2937; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background: #f9fafb; }
        .num { text-align: right; font-family: 'Courier New', monospace; font-weight: 600; }
        .pill { display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 0.75rem; font-weight: 600; }
        .p-ut { background: #fee2e2; color: #991b1b; } /* Red-ish for UT */
        .p-con { background: #dbeafe; color: #1e40af; } /* Blue-ish for Contractors */
    </style>
</head>
<body>

    <div class="nav">
        <h1>DASHBOARD UT SATUI - AS OF OCT 2025</h1>
        <form action="{{ route('logout') }}" method="POST">@csrf <button>LOGOUT</button></form>
    </div>

    <div class="box">
        <!-- Metric Cards -->
        <div class="cards">
            <div class="card">
                <h3>Total Manpower</h3>
                <div class="val">{{ number_format($stats['tot_mp']) }}</div>
                <div class="sub">Active Employees</div>
            </div>
            <div class="card">
                <h3>Total Manhours</h3>
                <div class="val">{{ number_format($stats['tot_mh']) }}</div>
                <div class="sub">Cumulative Hours</div>
            </div>
            <div class="card">
                <h3>Manpower Out</h3>
                <div class="val">{{ number_format($stats['out_mp']) }}</div>
                <div class="sub">Resigned / Terminated</div>
            </div>
             <div class="card" style="border-color: #3b82f6;">
                <h3>Wiki Docs</h3>
                <div class="val">{{ number_format($stats['docs']) }}</div>
                <div class="sub">System Articles</div>
            </div>
        </div>

        <!-- Summary Table -->
        <div class="sec-head">
            <h2>Summary Manpower & Manhours</h2>
        </div>
        
        <div class="tbl-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Company</th>
                        <th class="num">Manpower</th>
                        <th class="num">Manhours</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report as $row)
                    <tr>
                        <td>
                            <span class="pill {{ $row->worker_category == 'KARYAWAN' ? 'p-ut' : 'p-con' }}">
                                {{ $row->worker_category }}
                            </span>
                        </td>
                        <td>{{ $row->company }}</td>
                        <td class="num">{{ number_format($row->total_mp) }}</td>
                        <td class="num">{{ number_format($row->total_mh) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                {{-- Grand Total Row --}}
                <tr style="background:#f3f4f6; font-weight:bold;">
                    <td colspan="2" style="text-align:right;">GRAND TOTAL</td>
                    <td class="num">{{ number_format($stats['tot_mp']) }}</td>
                    <td class="num">{{ number_format($stats['tot_mh']) }}</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 2rem; text-align: center;">
            <a href="{{ route('docs.index') }}" style="color: #4b5563; text-decoration: none; border-bottom: 1px dashed #9ca3af;">Go to CMS Panel &rarr;</a>
        </div>
    </div>

</body>
</html>