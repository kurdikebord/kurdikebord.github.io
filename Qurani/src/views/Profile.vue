<template>
    <div>
        <div class="section_content">
        <div class="no_login_msg">
            مرحبا بك، {{ $store.state.user.name }}
        </div>
    </div>
    </div>
</template>

<script>
    export default {
        name: 'Profile'
    }
</script>
