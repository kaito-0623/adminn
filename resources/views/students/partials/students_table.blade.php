@foreach($students as $student)
    <tr>
        <td>{{ $student->grade }}</td>
        <td>{{ $student->name }}</td>
        <td><a href="{{ route('students.show', $student->id) }}">詳細表示</a></td>
    </tr>
@endforeach
