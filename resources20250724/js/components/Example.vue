<template>
    <div class="container">
        <div class="flex flex-row flex-wrap">
            <div class="col-md-8 col-md-offset-2">
                <div class="relative mb-6 bg-white border-gray-200 rounded-sm border">
                    <div class="text-mainAdminTextGrayDark bg-gray-100 border-gray-200 border-b-2 pb-2 px-5 pt-3 relative">Example Component</div>

                    <div class="bg-white m-0 p-5">
                        I'm an example component!
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
