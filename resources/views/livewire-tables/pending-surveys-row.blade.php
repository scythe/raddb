<x-livewire-tables::table.cell>
  <a href="{{ route('surveys.edit', $row->id) }} ">{{ $row->id }}</a>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
  <a href="{{ route('machines.show', $row->machine->id) }}">{{ $row->machine->description }}</a>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
  {{ $row->test_date }}
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
  {{ $row->type->test_type }}
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
  {{ $row->accession }}
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
  {{ $row->notes }}
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
{{-- TODO: Edit/Delete buttons seem very kludgy.  Need to fix this --}}
  @if(Auth::check())
    <a href="{{ route('surveys.edit', $row->id) }}" class="btn btn-default btn-xs" role="button" data-toggle="tooltip" title="Modify this survey">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
      </svg></a>
  @endif
</x-livewire-tables::table.cell>
