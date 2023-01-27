<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit quiz
            </h2>
            <Link className="px-6 py-2 text-white bg-blue-500 rounded-md focus:outline-none" :href="route('quizzes.index')">Quizzes</Link>
            </div>
        </template>
        <CategoryModal :show="showCatOptions" @close="showCatOptions = false" ></CategoryModal>
        <QuizForm :form="form" :categories="categories" :edit="true"></QuizForm>
    </AuthenticatedLayout>
</template>

<script setup>
import { computed, ref, reactive } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Label from '@/Components/InputLabel.vue';
import Input from '@/Components/TextInput.vue';
import TextArea from '@/Components/Textarea.vue';
import Checkbox from '@/Components/Checkbox.vue';
import QuizForm from '@/Components/QuizForm.vue'
import { Head, Link, useForm } from '@inertiajs/vue3';


const addQuestion = ref(false);
const questionIndex = ref(0);
const pageTab = ref('information');

const props = defineProps({
    quiz: Object,
    categories:Array,
});
const form = useForm({
    id: props.quiz.id,
    title: props.quiz.title,
    description: props.quiz.description,
    category_id: props.quiz.category.id,
    questions: props.quiz.questions
});

</script>

<style lang="scss" scoped>


</style>