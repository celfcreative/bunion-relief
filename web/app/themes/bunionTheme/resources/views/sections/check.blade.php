@foreach ($navigation as $item)
    @if ($item->children)
        <li class="my-menu-item nav-item dropdown">
            <a href="{{ $item->url }}"
                class="nav-link btn p-2 {{ $item->classes ?? '' }} {{ $item->active ? '' : '' }}" role="button"
                data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                {{ $item->label }}
            </a>
            <ul class="my-child-menu nav-item dropdown-menu rounded-0 bg-primary px-4 py-3"
                aria-labelledby="dropdownMenuButton">
                @foreach ($item->children as $child)
                    <li class="my-child-item {{ $child->active ? '' : '' }}">
                        <a href="{{ $child->url }}"
                            class="dropdown-item nav-link my-child-menu-link fw-normal lh-1 text-white">
                            {{ $child->label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    @else
        <li class="my-menu-item nav-item ">
            <a href="{{ $item->url }}"
                class="nav-link btn p-2 {{ $item->classes ?? '' }} {{ $item->active ? '' : '' }}">
                {{ $item->label }}
            </a>
        </li>
    @endif
@endforeach
