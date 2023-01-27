<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Input from '@/Components/TextInput.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref, watch  } from "vue";

let props = defineProps({
    quizzes: Array,
    filters: Object
});

const destroy = (id) => {
        if(confirm('Are you sure?')){
            Inertia.delete(route('quizzes.destroy', id));
        }
        return {destroy}
}
let search = ref(props.filters.search);
watch(search, value => { 
    Inertia.get('/quizzes', { search: value }, {
        preserveState: true,
        replace: true
    });
});




</script>
<template>
    <Head title="Dashboard" />
    <BreezeAuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div className="flex items-center justify-between">
                            <img  src="/images/hero3.png" class="w-100 h-100  fill-current text-gray-500" />
                        </div>
                    </div>
                </div>
                <div v-if=" $page.props.flash.message" class="">
                        <div class="p-2 ">
                            <div class="flex flex-col items-center">
                                    <div v-if=" $page.props.flash.message" class="text-blue-600 mb-4">
                                        {{ $page.props.flash.message }}
                                    </div>
                            </div>
                        </div>
                    </div>

                <div class="">
                    <Input v-model="search" type="text" class="mt-1 "  placeholder="Search...."/>
                </div>

      
                <div v-if="quizzes && quizzes.data.length > 0">
                 
                    <div v-for="quiz in quizzes.data" class="card bg-white overflow-hidden shadow-sm sm:rounded-lg my-4">
                        <Link :href="route('quizzes.show', quiz.id)" >
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex content-start justify-between">
                                <div>
                                    <h1><span>Title: </span>{{ quiz.title }}</h1>
                                    <h2><span>Description: </span>{{ quiz.description }}</h2>
                                </div>
                                <div class="flexflex-row space-between">
                                    <h2>Category: {{ quiz.category.title}}</h2>
                                    <div v-if="$page.props.auth.roles.includes('edit')" class="flex justify-end">
                                        <Link
                                            tabIndex="1"
                                            className="px-4 py-2 text-sm text-white bg-blue-500 rounded"
                                            :href="route('quizzes.edit', quiz.id)"
                                        >
                                            Edit
                                        </Link>
                                        <button
                                            @click="destroy(quiz.id)"
                                            tabIndex="-1"
                                            type="button"
                                            className="mx-1 px-4 py-2 text-sm text-white bg-red-500 rounded"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </Link>

                    </div>
                    <!-- paginator -->
                    <div>
                        <template v-for="link in quizzes.links">
                            <Link
                     
                            v-if="link.url"
                            :href="link.url" 
                            v-html="link.label"
                            :class="link.active ? 'text-blue-400' : ''"
                            class="px-1"
                            />
                            <span v-else
                            v-html="link.label"
                            :class="link.url ? '' : 'text-gray-400'"
                            >
                            </span>
                        </template>
                    </div>
                </div>
                <div v-else-if="filters.search != null">
                    <div class="card bg-white overflow-hidden shadow-sm sm:rounded-lg my-4">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex flex-col items-center">
                                <div>
                                   No quizzes matching your search!
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div class="card bg-white overflow-hidden shadow-sm sm:rounded-lg my-4">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex flex-col items-center">
                                <div>
                                    Currently no quizzes have been created!
                                </div>
                          
                                
                                    <Link  v-if="$page.props.auth.roles.includes('edit')"
                                    className="px-6 py-2 mt-6 text-white bg-green-500 rounded-md focus:outline-none"
                                    :href="route('quizzes.create')"
                                        >
                                            Create the first Quiz!
                                    </Link>
                          
                              
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<style scoped lang="scss">
.card{
    background: white;
    box-shadow:1px 1px 1px #CDD9E9;
    border-radius: 8px;
    h1{
        font-size: 1.2rem;
        font-weight: 900;
        color: #0E67B4;
    }
    h2{
        font-size: 1rem;
        font-weight: 700;
        // color: #0E67B4;
    }

}
.card:hover{
    box-shadow:1px 1px 8px #0E67B4;
 
    cursor:pointer;
}
</style>