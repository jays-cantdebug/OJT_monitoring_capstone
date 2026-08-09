@props(['entries', 'totalHours', 'totalMinutes'])

<table style="width: 100%; border-collapse: collapse; font-size: 13px;">
    <thead>
        <tr style="text-align: left; border-bottom: 2px solid #14213D;">
            <th style="padding: 8px 10px;">Student</th>
            <th style="padding: 8px 10px;">Date</th>
            <th style="padding: 8px 10px;">Time In</th>
            <th style="padding: 8px 10px;">Time Out</th>
            <th style="padding: 8px 10px;">Duration</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($entries as $entry)
            <tr style="border-bottom: 1px solid #E5E5E5;">
                <td style="padding: 8px 10px;">{{ $entry->user->name }}</td>
                <td style="padding: 8px 10px;">{{ $entry->time_in->format('M j, Y') }}</td>
                <td style="padding: 8px 10px;">{{ $entry->time_in->format('g:i A') }}</td>
                <td style="padding: 8px 10px;">
                    {{ $entry->time_out ? $entry->time_out->format('g:i A') : 'Still on duty' }}
                </td>
                <td style="padding: 8px 10px;">
                    @if ($entry->time_out)
                        @php $minutes = intdiv($entry->durationInSeconds(), 60); @endphp
                        {{ intdiv($minutes, 60) }}h {{ $minutes % 60 }}m
                    @else
                        &mdash;
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="padding: 20px 10px; text-align: center; color: #666;">No attendance records match these filters.</td>
            </tr>
        @endforelse
    </tbody>
    @if ($entries->isNotEmpty())
        <tfoot>
            <tr style="border-top: 2px solid #14213D; font-weight: bold;">
                <td colspan="4" style="padding: 8px 10px; text-align: right;">Total (completed entries):</td>
                <td style="padding: 8px 10px;">{{ $totalHours }}h {{ $totalMinutes }}m</td>
            </tr>
        </tfoot>
    @endif
</table>
