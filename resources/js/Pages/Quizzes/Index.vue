<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import BreezeAuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
defineProps({
    quizzes: Array,
});
// const form = useForm();
function destroy(id) {
    if (confirm("Are you sure you want to Delete")) {
        form.delete(route('quizzes.destroy', id));
    }
}
</script>
<template>
    <Head title="Dashboard" />
    <BreezeAuthenticatedLayout>
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <ApplicationLogo class="w-100 h-100 p-6 fill-current text-gray-500" />
            </h2>
        </template> -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div className="flex items-center justify-between">
                            <img  src="/images/hero3.png" class="w-100 h-100  fill-current text-gray-500" />
                        </div>
                    </div>
                </div>
                <div v-if="quizzes">
                    <div v-for="quiz in quizzes" class="card bg-white overflow-hidden shadow-sm sm:rounded-lg my-4">
                        <Link :href="route('quizzes.show', quiz.id)" >
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex content-start justify-between">
                                <div>
                                    <h1>{{ quiz.title }}</h1>
                                    <h2>{{ quiz.description }}</h2>
                                </div>
                                <div class="flexflex-row space-between">
                                    <h3>Category: {{ quiz.category.title}}</h3>
                                    <div v-if="$page.props.auth.roles.includes('edit')" >
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