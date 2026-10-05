<div>
    <!-- Very little is needed to make a happy life. - Marcus Aurelius -->
    @props(['type' => 'success', 'title' => 'Thông báo'])
    <div style="padding:10px;border-radius:8px;margin-bottom:10px; ackground: {{ $type === 'success' ? '#ECFDF5' : '#FEF3C7' }}; color: {{ $type === 'success' ? '#065F46' : '#92400E' }};">
        <strong>{{ $title }}:</strong> {{ $slot }}
    </div>
</div>