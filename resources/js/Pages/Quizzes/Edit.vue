<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit quiz
            </h2>
            <Link className="px-6 py-2 text-white bg-blue-500 rounded-md focus:outline-none" :href="route('quizzes.index')">Back</Link>
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

.marked-as-correct {
    box-shadow:1px 1px 5px #6fce79;

}
    .tabs-component-tabs {
        width: 100vw;
        display: flex;
        justify-content:space-between;
        white-space: nowrap;
    }

    .tabs-component-tab {
        height: 3rem;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #A3AFC0;
        font-size: 14px;
        font-weight: 600;
        list-style: none;
        position:relative;
    }

    .tabs-component-tab:hover {
        color: #666;
        text-shadow: 0px 0px 10px rgb(111, 71, 255,0.2);
        z-index: 2;
        -webkit-box-shadow: inset 0px 0px 2px #efefef;
           -moz-box-shadow: inset 0px 0px 2px #efefef;
                box-shadow: inset 0px 0px 2px #efefef;
        outline: none;
        cursor: pointer;
    }
    .tabs-component-tab.is-active {
        // border:2px solid rgba(166, 165, 166, 0.4); 
        border-bottom: none;
        border-radius:5px 5px 0px 0px ;
        color: black;
        text-shadow: 0px 0px 10px rgb(111, 71, 255,0.2);
        z-index: 2;
        transform: translateY(1px);
        outline: none;
    }
    .tabs-component-tab.not-active {
         background: #F8FAFC !important;
         -webkit-box-shadow: inset 0px 0px 5px #efefef;
           -moz-box-shadow: inset 0px 0px 5px #efefef;
                box-shadow: inset 0px 0px 5px #efefef;
        outline: none;
    }
</style>