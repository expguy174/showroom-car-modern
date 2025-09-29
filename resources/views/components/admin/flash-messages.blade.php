@props(['showIcons' => true, 'dismissible' => true, 'position' => 'top-right', 'autoHide' => 5000])

@php
    // Define position classes function
    $getPositionClasses = function() use ($position) {
        switch($position ?? 'top-right') {
            case 'top-left':
                return 'top-4 left-4';
            case 'top-center':
                return 'top-4 left-1/2 transform -translate-x-1/2';
            case 'top-right':
                return 'top-4 right-4';
            case 'bottom-left':
                return 'bottom-4 left-4';
            case 'bottom-center':
                return 'bottom-4 left-1/2 transform -translate-x-1/2';
            case 'bottom-right':
                return 'bottom-4 right-4';
            default:
                return 'top-4 right-4';
        }
    };
@endphp

{{-- Meta tags for JavaScript access --}}
@if(session('success'))
    <meta name="flash-success" content="{{ session('success') }}">
@endif
@if(session('error'))
    <meta name="flash-error" content="{{ session('error') }}">
@endif
@if(session('warning'))
    <meta name="flash-warning" content="{{ session('warning') }}">
@endif
@if(session('info'))
    <meta name="flash-info" content="{{ session('info') }}">
@endif

{{-- Flash Messages Container --}}
<div id="flash-messages-container" class="fixed {{ $getPositionClasses() }} z-50 space-y-3 max-w-md">
    {{-- Success Message --}}
    @if(session('success'))
    <div class="flash-message bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-lg flex items-start space-x-3"
         data-type="success">
        @if($showIcons)
        <div class="flex-shrink-0">
            <i class="fas fa-check-circle text-green-500 text-lg"></i>
        </div>
        @endif
        <div class="flex-1">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
        @if($dismissible)
        <button type="button" class="flex-shrink-0 text-green-500 hover:text-green-700" onclick="dismissMessage(this)">
            <i class="fas fa-times"></i>
        </button>
        @endif
    </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
    <div class="flash-message bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-lg flex items-start space-x-3"
         data-type="error">
        @if($showIcons)
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
        </div>
        @endif
        <div class="flex-1">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
        @if($dismissible)
        <button type="button" class="flex-shrink-0 text-red-500 hover:text-red-700" onclick="dismissMessage(this)">
            <i class="fas fa-times"></i>
        </button>
        @endif
    </div>
    @endif

    {{-- Warning Message --}}
    @if(session('warning'))
    <div class="flash-message bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg shadow-lg flex items-start space-x-3"
         data-type="warning">
        @if($showIcons)
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-lg"></i>
        </div>
        @endif
        <div class="flex-1">
            <p class="font-medium">{{ session('warning') }}</p>
        </div>
        @if($dismissible)
        <button type="button" class="flex-shrink-0 text-yellow-500 hover:text-yellow-700" onclick="dismissMessage(this)">
            <i class="fas fa-times"></i>
        </button>
        @endif
    </div>
    @endif

    {{-- Info Message --}}
    @if(session('info'))
    <div class="flash-message bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg shadow-lg flex items-start space-x-3"
         data-type="info">
        @if($showIcons)
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-500 text-lg"></i>
        </div>
        @endif
        <div class="flex-1">
            <p class="font-medium">{{ session('info') }}</p>
        </div>
        @if($dismissible)
        <button type="button" class="flex-shrink-0 text-blue-500 hover:text-blue-700" onclick="dismissMessage(this)">
            <i class="fas fa-times"></i>
        </button>
        @endif
    </div>
    @endif
</div>

{{-- Dynamic Flash Messages Container (for JavaScript) --}}
<div id="dynamic-flash-messages" class="fixed {{ $getPositionClasses() }} z-50 space-y-3 max-w-md"></div>

@push('scripts')
<script>
// Flash Messages Management
class FlashMessagesManager {
    constructor(options = {}) {
        this.autoHide = options.autoHide || {{ $autoHide }};
        this.container = document.getElementById('dynamic-flash-messages');
        this.init();
    }
    
    init() {
        // Auto-hide existing messages
        this.autoHideMessages();
        
        // Handle Laravel session flash messages from meta tags
        this.handleSessionMessages();
        
        // Make showMessage globally available
        window.showMessage = (message, type = 'info', autoHide = true) => {
            this.show(message, type, autoHide);
        };
        
        // Make dismissMessage globally available
        window.dismissMessage = (button) => {
            this.dismiss(button.closest('.flash-message'));
        };
    }
    
    handleSessionMessages() {
        const types = ['success', 'error', 'warning', 'info'];
        
        types.forEach(type => {
            const meta = document.querySelector(`meta[name="flash-${type}"]`);
            if (meta) {
                const message = meta.getAttribute('content');
                if (message) {
                    // Clean message (remove emojis)
                    const cleanMessage = message.replace(/[✅❌⚠️ℹ️]/g, '').trim();
                    this.show(cleanMessage, type, true);
                }
            }
        });
    }
    
    show(message, type = 'info', autoHide = true) {
        const messageEl = this.createMessageElement(message, type);
        this.container.appendChild(messageEl);
        
        // Animate in
        setTimeout(() => {
            messageEl.classList.add('opacity-100', 'translate-y-0');
            messageEl.classList.remove('opacity-0', 'translate-y-2');
        }, 10);
        
        // Auto hide
        if (autoHide && this.autoHide > 0) {
            setTimeout(() => {
                this.dismiss(messageEl);
            }, this.autoHide);
        }
    }
    
    createMessageElement(message, type) {
        const colors = {
            success: {
                bg: 'bg-green-50',
                border: 'border-green-200',
                text: 'text-green-800',
                icon: 'fas fa-check-circle text-green-500',
                button: 'text-green-500 hover:text-green-700'
            },
            error: {
                bg: 'bg-red-50',
                border: 'border-red-200',
                text: 'text-red-800',
                icon: 'fas fa-exclamation-circle text-red-500',
                button: 'text-red-500 hover:text-red-700'
            },
            warning: {
                bg: 'bg-yellow-50',
                border: 'border-yellow-200',
                text: 'text-yellow-800',
                icon: 'fas fa-exclamation-triangle text-yellow-500',
                button: 'text-yellow-500 hover:text-yellow-700'
            },
            info: {
                bg: 'bg-blue-50',
                border: 'border-blue-200',
                text: 'text-blue-800',
                icon: 'fas fa-info-circle text-blue-500',
                button: 'text-blue-500 hover:text-blue-700'
            }
        };
        
        const color = colors[type] || colors.info;
        
        const messageEl = document.createElement('div');
        messageEl.className = `flash-message ${color.bg} border ${color.border} ${color.text} px-4 py-3 rounded-lg shadow-lg flex items-start space-x-3 opacity-0 translate-y-2 transition-all duration-300`;
        messageEl.setAttribute('data-type', type);
        
        messageEl.innerHTML = `
            @if($showIcons)
            <div class="flex-shrink-0">
                <i class="${color.icon} text-lg"></i>
            </div>
            @endif
            <div class="flex-1">
                <p class="font-medium">${message}</p>
            </div>
            @if($dismissible)
            <button type="button" class="flex-shrink-0 ${color.button}" onclick="dismissMessage(this)">
                <i class="fas fa-times"></i>
            </button>
            @endif
        `;
        
        return messageEl;
    }
    
    dismiss(messageEl) {
        if (!messageEl) return;
        
        messageEl.classList.add('opacity-0', 'translate-y-2');
        messageEl.classList.remove('opacity-100', 'translate-y-0');
        
        setTimeout(() => {
            if (messageEl.parentNode) {
                messageEl.parentNode.removeChild(messageEl);
            }
        }, 300);
    }
    
    autoHideMessages() {
        if (this.autoHide <= 0) return;
        
        const existingMessages = document.querySelectorAll('.flash-message');
        existingMessages.forEach(message => {
            setTimeout(() => {
                this.dismiss(message);
            }, this.autoHide);
        });
    }
}

// Initialize Flash Messages Manager
document.addEventListener('DOMContentLoaded', function() {
    new FlashMessagesManager({
        autoHide: {{ $autoHide }}
    });
});
</script>
@endpush
