<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                   Create Quiz
                </h2>
                <Link className="px-6 py-2 text-white bg-blue-500 rounded-md focus:outline-none" :href="route('quizzes.index')">Back</Link>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="tabs-component flex">
                        <ul class="tabs-component-tabs">
                            <li @click.prevent="quizInfo" class="tabs-component-tab" :class="pageTab == 'information' ? 'is-active' : 'not-active' " >
                                <a class="class-tabs-component-tab-a" >
                                    Information
                                </a>
                            </li>
                            <li  @click.prevent="quizQuestions" class="tabs-component-tab" :class="pageTab == 'questions' ? 'is-active' : 'not-active' ">
                                <a class="class-tabs-component-tab-a">
                                    Questions
                                </a>
                            </li>
                        </ul>
                    </div> 
                    <div class="p-6 bg-white border-b border-gray-200">
                        
                        <div className="flex items-center  mb-6">
                       
                        </div>
                        
                        <form name="createForm" @submit.prevent="submit">
                                <div v-if="pageTab == 'information'" className="flex flex-col">
                                    <div className="mb-4">
                                        <Label for="title" value="Title" />
                                        <Input 
                                            id="title" 
                                            type="text" 
                                            class="mt-1 block w-full" 
                                            v-model="form.title" 
                                            autofocus />
                                        <span className="text-red-600" v-if="form.errors.title">
                                            {{ form.errors.title }}
                                        </span>
                                    </div>
                                    <div className="mb-4">
                                        <Label for="description" value="Description" />
                                        
                                        <TextArea 
                                            id="body" 
                                            class="mt-1 block w-full" 
                                            v-model="form.description" 
                                            autofocus />
                                        <span className="text-red-600" v-if="form.errors.description">
                                            {{ form.errors.description }}
                                        </span>
                                    </div>
                                    <div className="mb-4">
                                        <Label for="category" value="Category" />
                                        <select v-model="form.category_id" class="form-control mt-1 block w-full">
                                            <option v-for="option in categories" :value="option.id">
                                                {{ option.title}}
                                            </option>
                                        </select>
                                        <span className="text-red-600" v-if="form.errors.category">
                                            {{ form.errors.category_id }}
                                        </span>
                                        <span className="text-red-600" v-if="form.errors.title">
                                            {{ form.errors.title }}
                                        </span>
                                    </div>
                                    <div class="flex justify-end">
                                        <button className="px-6 py-2 text-white bg-green-500 rounded-md focus:outline-none" v-if="questions.length == 0" @click.prevent="startQuestion(index)"> start question </button>
                                    </div>
                                </div>
                                <div v-else-if="pageTab != 'information'">
                                    <div v-for="(question , index) in form.questions" :key="index">
                                        <!-- <div v-show="index == page - 1"> -->
                                        <div >
                                            <div className="mb-4">
                                                <Label for="question" :value="`Question ${index+1}`" />
                                                <TextArea  
                                                    id="question" 
                                                    type="text" 
                                                    class="mt-1 block w-full" 
                                                    v-model="question.question" 
                                                    autofocus />
                                                <span className="text-red-600" v-if="form.errors.title">
                                                    {{ form.errors.title }}
                                                </span>
                                                <div v-for="(answer, number) in question.answers"  :key="number" className="mb-4">
                                                    <Label for="answer" :value="`Answer ${number + 1}`" />   <div class="block mt-4">
                                                        <label class="flex items-center">
                                                            <Checkbox name="remember" v-model:checked="answer.correct_answer" />
                                                            <span class="ml-2 text-sm text-gray-600">Mark as correct</span>
                                                        </label>
                                                    </div>
                                                    <TextArea 
                                                        id="body" 
                                                        class="mt-1 block w-full" 
                                                        v-model="answer.answer" 
                                                        autofocus />
                                                    <span className="text-red-600" v-if="form.errors.description">
                                                        {{ form.errors.description }}
                                                    </span>
                                                    <!-- <button @click.prevent="newAnswer(number)"> add answer </button> -->
                                                <!-- </div> -->
                                                </div>
                                            </div>
                                            <div class="flex justify-between">
                                                <button v-if="form.questions[index].answers.length <= 4" className="px-6 py-2 text-white bg-blue-500 rounded-md focus:outline-none" @click.prevent="newAnswer(index)"> add answer </button>
                                                <button className="px-6 py-2 text-white bg-green-500 rounded-md focus:outline-none" :id="number" v-if="form.questions[index].answers.length >= 2 && form.questions.length == index + 1 " @click.prevent="newQuestion(number)"> add question </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             
  
                                <div className="mt-4">
                                    <button
                                        type="submit"
                                        className="px-6 py-2 font-bold text-white bg-green-500 rounded"
                                    >
                                        Save
                                    </button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { computed, ref, reactive } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Label from '@/Components/InputLabel.vue';
import Input from '@/Components/TextInput.vue';
import TextArea from '@/Components/Textarea.vue';
import DropDown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';


const addQuestion = ref(false);
const questionIndex = ref(0);
const questions = ref([]);
const pageTab = ref('information')

defineProps({
    categories: Array,
});

const form = useForm({
        title: '',
        description: '',
        category_id: '',
        questions: [{
            answers:[{
                answer: '',
                correct_answer: false
            }],
                question:'',
            }]
    });

const submit = () => {
    form.post(route('quizzes.store'));
};

const createQuiz = () => {
    form.post(route('quizzes.store'));
    // addQuestion = true
};

const  startQuestion = () => {
    pageTab.value = 'questions'
    addQuestion.value = true
    form.questions.push({
        question:'',
        answers:[]
    })
};
const  newQuestion = (index) => {
    // page.value = ++questionIndex.value 
    addQuestion.value = true
    form.questions.push({
        question:'',
        answers:[]
    })
    
};
const  newAnswer = (index) => {
    form.questions[index].answers.push({
        answer:''
    })
};

const  quizInfo = () => {
    pageTab.value = 'information'
};

const  quizQuestions = () => {
    pageTab.value = 'questions'
    addQuestion.value = true
    // initialise first question if one hasntallready been created
    if(form.questions.value.length  < 1){
        form.questions.push({
        question:'',
        answers:[]
    }) 
    }
};

</script>

<style lang="scss" scoped>
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