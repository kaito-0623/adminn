@foreach($grades as $schoolGrade)
    <tr class="grade-row" data-grade="{{ $schoolGrade->grade }}" data-term="{{ $schoolGrade->term }}">
        <td>{{ $schoolGrade->term }}</td>
        <td>{{ $schoolGrade->grade }}</td>
        <td>{{ $schoolGrade->japanese }}</td>
        <td>{{ $schoolGrade->math }}</td>
        <td>{{ $schoolGrade->science }}</td>
        <td>{{ $schoolGrade->social_studies }}</td>
        <td>{{ $schoolGrade->music }}</td>
        <td>{{ $schoolGrade->home_economics }}</td>
        <td>{{ $schoolGrade->english }}</td>
        <td>{{ $schoolGrade->art }}</td>
        <td>{{ $schoolGrade->health_and_physical_education }}</td>
        <td>
            <button onclick="window.location.href='{{ route('schoolGrades.edit', $schoolGrade->id) }}'">編集</button>
        </td>
    </tr>
@endforeach
