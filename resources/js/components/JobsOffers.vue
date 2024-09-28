<template>
    <div class="p-6 sm:p-10 text-2xl font-bold md:pl-32">

        <h3>Jobs</h3>
        <div class="grid  lg:grid-cols-2 xl:grid-cols-3 gap-4 mt-20 md:mt-10">
            <div
                class="bg-white mt-10 shadow-lg w-full max-w-4xl flex flex-col sm:flex-row gap-3 sm:items-center  justify-between px-5 py-4 rounded-md cursor-pointer">
                <div class="w-full">
                    <div class="flex justify-between">
                        <h3 class="font-bold mt-px text-blue-800">Job offer name</h3>
                        <button class="bg-blue-500 text-white font-medium p-2 rounded-3xl flex gap-1 items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                    <div class="text-sm font-medium">
                        <span class="m-1 ml-0 inline-block">HTML </span>
                        <span class="m-1 ml-0 inline-block">CSS </span>
                        <span class="m-1 ml-0 inline-block">FIGMA </span>
                        <span class="m-1 ml-0 inline-block">Ad. XD </span>
                        <span class="m-1 ml-0 inline-block">Illustrator </span>
                    </div>
                    <span class="text-slate-600 text-sm flex gap-1 items-center"> <svg
                            xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg> Location id name, location id postal code</span>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="bg-blue-500 text-white rounded-full px-3 py-1 text-sm">contract_id name</span>
                        <span class="text-blue-500 text-sm">Type_id name</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>

</script>
