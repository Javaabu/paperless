<div class="card wizard-card">
    <ul class="nav nav-tabs">
        @foreach($tabs as $key => $tab)
            @php
                $url = is_admin_portal() ? data_get($tab, 'url') : data_get($tab, 'public_url');
                $active = is_admin_portal() ? $tab['active'] : $tab['public_active'];
                $icon = array_key_exists('admin_icon', $tab) || array_key_exists('public_icon', $tab);
                $tab_icon = is_admin_portal() ? data_get($tab, 'admin_icon') : data_get($tab, 'public_icon');
                $disabled = $tab['disabled'] ?? false;
            @endphp
            <li class="nav-item">
                <a  href="{{ $url }}"
                   class="nav-link {{ $active ? 'active' : '' }} {{ $disabled ? 'disabled' : '' }}">
                        <span class="step-num">
                            @if($icon)
                                <i class="{{ $tab_icon }}"></i>
                            @else
                                {{ $loop->index + 1 }}
                            @endif
                        </span>
                    <span class="mx-1">{{ str($tab['title'])->ucfirst()  }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
