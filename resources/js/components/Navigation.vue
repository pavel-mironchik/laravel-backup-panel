<template>
    <ul class="nav flex-column">
        <li class="nav-item">
            <router-link active-class="active" class="nav-link d-flex align-items-center pt-0" to="/files">
                <svg class="bi bi-window" fill="currentColor" height="1.5em" viewBox="0 0 20 20" width="1.5em"
                     xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd"
                          d="M16 4H4a1 1 0 00-1 1v10a1 1 0 001 1h12a1 1 0 001-1V5a1 1 0 00-1-1zM4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4z"
                          fill-rule="evenodd"></path>
                    <path clip-rule="evenodd" d="M17 8H3V7h14v1z" fill-rule="evenodd"></path>
                    <path
                            d="M5 5.5a.5.5 0 11-1 0 .5.5 0 011 0zm1.5 0a.5.5 0 11-1 0 .5.5 0 011 0zm1.5 0a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                </svg>
                <span>Files</span>
            </router-link>
        </li>
        <li class="nav-item">
            <router-link active-class="active" class="nav-link d-flex align-items-center" to="/path">
                <svg class="bi bi-window" fill="currentColor" height="1.5em" viewBox="0 0 20 20" width="1.5em"
                     xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd"
                          d="M16 4H4a1 1 0 00-1 1v10a1 1 0 001 1h12a1 1 0 001-1V5a1 1 0 00-1-1zM4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4z"
                          fill-rule="evenodd"></path>
                    <path clip-rule="evenodd" d="M17 8H3V7h14v1z" fill-rule="evenodd"></path>
                    <path
                            d="M5 5.5a.5.5 0 11-1 0 .5.5 0 011 0zm1.5 0a.5.5 0 11-1 0 .5.5 0 011 0zm1.5 0a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                </svg>
                <span>Menu item</span>
            </router-link>
        </li>
        <li class="nav-item">
            <router-link active-class="active" class="nav-link d-flex align-items-center" to="/path">
                <svg class="bi bi-window" fill="currentColor" height="1.5em" viewBox="0 0 20 20" width="1.5em"
                     xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd"
                          d="M16 4H4a1 1 0 00-1 1v10a1 1 0 001 1h12a1 1 0 001-1V5a1 1 0 00-1-1zM4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4z"
                          fill-rule="evenodd"></path>
                    <path clip-rule="evenodd" d="M17 8H3V7h14v1z" fill-rule="evenodd"></path>
                    <path
                            d="M5 5.5a.5.5 0 11-1 0 .5.5 0 011 0zm1.5 0a.5.5 0 11-1 0 .5.5 0 011 0zm1.5 0a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                </svg>
                <span>Menu item</span>
            </router-link>
        </li>
    </ul>
</template>

<script>
    export default {
        name: 'Navigation'
    }
</script>

<style scoped>
    .nav-item a svg {
        margin-right: 10px;
        fill: #a2aeb7;
    }

    .nav-item a.active svg {
        fill: #00897B;
    }

    .nav-item a {
        color: #3d434a;
    }

    .nav-item a.active {
        color: #00897B
    }
</style>
