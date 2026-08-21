<ul class="navbar-nav mx-auto">
    <li class="nav-item">
        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About Us</a>
    </li>
    <li class="nav-item">
        <a href="javascript:void(0)" class="dropdown-toggle nav-link">Personal Banking</a>
        <ul class="dropdown-menu list-unstyle">
            <li class="nav-item"><a href="{{ route('personal.banking-services') }}" class="nav-link">Checking &amp; Savings</a></li>
            <li class="nav-item"><a href="{{ route('transfer') }}" class="nav-link">Transfers &amp; Payments</a></li>
            <li class="nav-item"><a href="{{ route('deposit') }}" class="nav-link">Deposits</a></li>
            <li class="nav-item"><a href="{{ route('invest') }}" class="nav-link">Investing</a></li>
        </ul>
    </li>
    <li class="nav-item">
        <a href="{{ route('personal.customer-support') }}" class="nav-link">Help Center</a>
    </li>
    <li class="nav-item">
        <a href="{{ route('about') }}#contact" class="nav-link">Contact Us</a>
    </li>
</ul>
