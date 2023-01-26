<template>
     <div class="py-12">
        <CategoryModal :show="showCatOptions" @close="showCatOptions = false" ></CategoryModal>
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
                        <form name="form" @submit.prevent="submit">
                                <div v-if="pageTab == 'information'" className="flex flex-col">
                                    <div className="mb-4">
                                        <Label for="title" value="Title" />
                                        <Input 
                                            id="title" 
                                            type="text" 
                                            class="mt-1 block w-full" 
                                            v-model="props.form.title" 
                                            autofocus />
                                        <span className="text-red-600" v-if="props.form.errors.title">
                                            {{ props.form.errors.title }}
                                        </span>
                                    </div>
                                    <div className="mb-4">
                                        <Label for="description" value="Description" />
                                        <TextArea 
                                            id="body" 
                                            class="mt-1 block w-full" 
                                            v-model="form.description" 
                                            autofocus />
                                        <span className="text-red-600" v-if="props.form.errors.description">
                                            {{ props.form.errors.description }}
                                        </span>
                                    </div>
                                    <div className="mb-4">
                                        <div class="flex  justify-between" >
                                        <Label for="category" value="Category" /> 
                                        <button className="px-2 text-white bg-blue-500 rounded-md focus:outline-none text-sm" @click.prevent="newCategory()">Create category</button>
                                        </div>
                                        <select v-model="props.form.category_id" class="form-control mt-1 block w-full">
                                            <option v-for="option in categories" :value="option.id">
                                                {{ option.title}}
                                            </option>
                                        </select>
                                        <span className="text-red-600" v-if="props.form.errors.category_id">
                                            {{ props.form.errors.category_id }}
                                        </span>
                                    </div>
                                    <div class="flex justify-end">
                                        <button className="px-6 py-2 text-white bg-green-500 rounded-md focus:outline-none" v-if="questions.length == 0" @click.prevent="startQuestion(index)">Add Questions</button>
                                    </div>
                                </div>
                                <div v-else-if="pageTab != 'information'">
                                    <div v-for="(question , index) in props.form.questions" :key="index" class="mb-4">
                                        <div>
                                            <!-- question -->
                                            <div class="mb-4">
                                                <div class="question my-10">
                                                    <div class="flex  justify-between" >
                                                        <Label for="question" :value="`Question ${index+1}`" />
                                                            <button class="text-stone-500 border border-stone-500 hover:bg-stone-500 hover:text-white active:bg-stone-600 font-bold rounded outline-none focus:outline-none ease-linear transition-all duration-150 text-sm"  @click.prevent="removeQuestion(index)">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>   
                                                    </div>
                                                    <TextArea  
                                                        id="question" 
                                                        type="text" 
                                                        class="mt-1 block w-full" 
                                                        v-model="question.question" 
                                                        autofocus />
                                                    <span className="text-red-600" v-if="props.form.errors[`questions.${index}.question`]">
                                                        {{props.form.errors[`questions.${index}.question`]}}
                                                    </span>
                                                </div>
                                                <!-- answer -->
                                                <div v-if="question.answers.length > 0" class="answers p-6">
                                                    <div class="flex flex-col items-center">
                                                        <span className="text-red-600" v-if="props.form.errors['questions']">
                                                            {{ props.form.errors['questions'] }}
                                                        </span>
                                                    
                                                        <span className="text-red-600" v-if="props.form.errors[`questions.${index}.answers`]">
                                                            {{ props.form.errors[`questions.${index}.answers`] }}
                                                        </span>
                                                    </div>
                                                  
                                                    <div v-for="(answer, number) in question.answers"  :key="number" className="my-4">
                                                    <div class="block mt-4">
                                                        <div class="flex justify-between">
                                                            <Label for="answer" :value="`Answer ${number + 1}`" />   
                                                            <div>
                                                                <label class="flex items-center">
                                                                    <Checkbox name="remember" v-model:checked="answer.correct_answer" />
                                                                    <span class="ml-2 text-sm text-gray-600">Mark as correct</span>
                                                                </label>
                                                               
                                                            </div>
                                                            <div>
                                                                <button class="text-stone-500 border border-stone-500 hover:bg-stone-500 hover:text-white active:bg-stone-600 font-bold rounded outline-none focus:outline-none ease-linear transition-all duration-150 text-sm"  @click.prevent="removeAnswer(index,number,answer)">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </button>            
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <TextArea 
                                                        id="body" 
                                                        class="mt-1 block w-full" 
                                                        :class="answer.correct_answer == true ? 'marked-as-correct' : '' "
                                                        v-model="answer.answer" 
                                                        autofocus />
                                                        <span v-if="props.form.errors[`questions.${index}.answers.${number}.answer`]" className="text-red-600">
                                                                {{ props.form.errors[`questions.${index}.answers.${number}.answer`] }}
                                                        </span>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="flex justify-between">
                                                <button v-if="props.form.questions[index].answers.length <= 4" className="px-6 py-2 text-white bg-blue-500 rounded-md focus:outline-none" @click.prevent="newAnswer(index)"> Add answer </button>
                                                <button v-if="props.form.questions[index].answers.length >= 3 && props.form.questions.length == index + 1" @click.prevent="newQuestion(number)" class="px-6 py-2 text-white bg-green-500 rounded-md focus:outline-none"> add question </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             
                                <!-- hide button untill a question has been added -->
                                <div  class="mt-4 flex justify-end">
                                    <button
                                        type="submit"
                                        className="px-6 py-2 font-bold text-white bg-green-500 rounded"
                                    >Save
                                    </button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
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
import CategoryModal from '@/Components/CategoryModal.vue'
import { Head, Link, useForm, router} from '@inertiajs/vue3';


const addQuestion = ref(false);
const questionIndex = ref(0);
const questions = ref([]);
const pageTab = ref('information')
const showCatOptions = ref(false)

const props = defineProps({
    categories: Array,
    form:Object,
    edit:Boolean
});


const newCategory = () => {
    showCatOptions.value = true
}

const submit = () => {
    if(props.edit == true){
        props.form.put(route('quizzes.update', props.form.id));
    }else{
        props.form.post(route('quizzes.store'));
    }
};


const createQuiz = () => {
    props.form.post(route('quizzes.store'));
    // addQuestion = true
};

const  startQuestion = () => {
    pageTab.value = 'questions'
    addQuestion.value = true
    if( props.form.questions == false || props.form.questions.value.length  < 1){
        props.form.questions.push({
        question:'',
        answers:[]
    })
}
};
const  newQuestion = (index) => {
    // page.value = ++questionIndex.value 
    addQuestion.value = true
    props.form.questions.push({
        question:'',
        answers:[]
    }) 
};

const removeQuestion = (index) => {
    props.form.questions.splice(index, 1);
    if(props.form.questions == false){
        pageTab.value = 'information'
    }
}

const  newAnswer = (index) => {
    props.form.questions[index].answers.push({
        answer:''
    })
};

const  removeAnswer = (index, number,answer) => {
    props.form.questions[index].answers.splice(number, 1)
    // Inertia.delete(route('answers.destroy', answer.id));
};

const  quizInfo = () => {
    pageTab.value = 'information'
};

const  quizQuestions = () => {
    pageTab.value = 'questions'
    addQuestion.value = true
    // initialise first question if one hasntallready been created
    if(props.form.questions == false || props.form.questions.value.length  < 1){
        props.form.questions.push({
        question:'',
        answers:[]
    }) 
    }
};

router.on('error', (errors) => {
    // route to correct tab if there are errors
    if(props.form.errors.title || props.form.errors.description || props.form.errors.information){
        pageTab.value = 'information' 
    } else {
        pageTab.value = 'questions' 
    }
//  console.log(form.errors);
})


</script>

<style lang="scss" scoped>

.answers{
    border-radius:5px 5px 5px 5px ;
    border: 1px solid #efefef;;
    // background: #F8FAFC !important;
    box-shadow:inset 1px 1px 4px #efefef;
    box-shadow: 0px 0px 3px #0E67B4;
    outline: none;
}
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