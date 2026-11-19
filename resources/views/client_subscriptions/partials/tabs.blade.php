{{-- #114: the three client subscription screens share one nav: plans, subscribers and payments. --}}
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link {{ $active === 'plans' ? 'active' : '' }}" href="{!! route('client-subscriptions') !!}">
            {{ trans('lang.client_subscription_plans') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active === 'subscribers' ? 'active' : '' }}" href="{!! route('client-subscriptions.subscribers') !!}">
            {{ trans('lang.client_subscription_subscribers') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $active === 'payments' ? 'active' : '' }}" href="{!! route('client-subscriptions.payments') !!}">
            {{ trans('lang.client_subscription_payments') }}
        </a>
    </li>
</ul>
