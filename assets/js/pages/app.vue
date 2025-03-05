<template>
     <h3>{{ monthName }}</h3>

     <div>
        <div v-for="(n, index) in dates" class="day_item _flex">
            <template v-if="n >= currentDate">
                <div class="day_item_td">{{ n }}</div>
                <div>
                    <div v-for="record in records">
                        <template v-if="n == record.date">
                            <div>{{ record.time }}</div>
                            <div>{{ record.item }}</div>
                        </template>
                    </div>
                </div>
                <div class="add_icon" @click="addItem(n)">+</div>
            </template>
        </div>
    </div>


    <section class="modal" :class="{ active: isActiveForm }" id="modal-success">
        <div class="modal__title">Добавить</div>

        <form action="" class="form modal__form" @submit.prevent="onSubmit">
            <div class="form__line">
                <div class="form__field">
                    <div class="form__label">Время</div>

                    <input type="text" value="" v-model="time" placeholder="12:00" class="form__input">
                </div>
            </div>

            <div class="form__line">
                <div class="form__field">
                    <div class="form__label">Событие</div>

                    <textarea placeholder="Введите ваше событие ..." v-model="item" class="form__textarea" :class="{ error: notValidItem }"></textarea>
                </div>
            </div>

            <div class="form__submit">
                <button type="submit" class="btn-green form__submit-btn">Добавить</button>
            </div>

        </form>
    </section>
</template>

<script>
import axios from 'axios'

export default {
    name: "app",
    data() {
        return {
            monthName: '',
            day: 0,
            month: 0,
            year: 0,
            entity: 0,
            time: '',
            item: '',
            isActiveForm: false,
            notValidTime: false,
            notValidItem: false,
            selectedDate: '',
            records: [],
            dates: []
        }
    },
    computed: {
        currentDate() {
            return this.year + '.' + this.month + '.' + this.day;
        },
    },
    props: {
        options: Object,
    },
    methods: {
        addItem: function(n){
            this.isActiveForm = true
            this.selectedDate = new Date(n)
            console.log(this.selectedDate)
        },
        async onSubmit(){
            let isValid = true;

            if(this.item == ''){
                this.notValidItem = true
                isValid = false
            }
            
            if(isValid){
                await axios.post('/api/add_item/', {
                    time: this.time,
                    item: this.item,
                    date: this.selectedDate
                }).then((response) => {
                    console.log(response)
                    this.isActiveForm = false
                    this.time = ''
                    this.item = ''
                    
                    axios.get('/api/shedule/').then((response) => {
                        this.monthName = response.data.monthName
                        this.day = response.data.day
                        this.month = response.data.month
                        this.year = response.data.year
                        this.entity = response.data.entity
                        this.records = response.data.records
                        this.dates = response.data.dates
                        console.log(response.data)
                    });
                });
            }
            // let productReview = {
            //     name: this.name,
            //     review: this.review,
            //     rating: this.rating,
            //     recommend: this.recommend
            // }
            // this.$emit('review-submitted', productReview)

            // this.name = ''
            // this.review = ''
            // this.rating = null
            // this.recommend = false
        }
    },
    mounted() {
    },
    updated() {
    },
    unmounted() {
        
    },
    async created () {
        await axios.get('/api/shedule/').then((response) => {
            this.monthName = response.data.monthName
            this.day = response.data.day
            this.month = response.data.month
            this.year = response.data.year
            this.entity = response.data.entity
            this.records = response.data.records
            this.dates = response.data.dates
            console.log(response.data)
        });
    }
}
</script>

<style scoped>
button {
  color: inherit;
  font-family: inherit;
  font-size: inherit;
  font-weight: inherit;
  line-height: inherit;
  display: inline-block;
  cursor: pointer;
  vertical-align: top;
  border: none;
  background: none;
}
.day_item {
    position: relative;
}
.day_item_td {
    padding: 10px;
}
.add_icon {
    position: absolute;
    right: 10px;
    top: 13px;
    border: 2px solid #221387;
    border-radius: 50%;
    background: #fff;
    color: #221387;
    width: 20px;
    height: 20px;
    justify-content: center;
    align-items: center;
    display: flex;
    font-weight: 700;
    font-size: 22px;
    cursor: pointer;
}

/*------------
    Modal
------------*/
.modal
{
    position: fixed;
    top: 0;
    left: 0;

    z-index: 3;

    display: none;
    visibility: visible !important;

    width: 100%;
    max-width: 100%;
    padding: 35px 0;
    height: 100%;
    border-radius: 10px;
    background: #FFF;
}
.modal.active {
    display: block;
}

.modal_feedback{
	width: 660px;
	padding: 50px;
}

.modal__head
{
    display: flex;
    justify-content: flex-start;
    align-content: center;
    align-items: center;
    flex-wrap: wrap;
}

.modal__title
{
    color: #000;
    font-size: 25px;
    font-weight: 700;
    line-height: 1.4;
    text-align: center;
}

.modal__head .modal__title
{
    margin-right: 20px;
}

.modal__head-btn
{
    color: #44943D;
    font-size: 13px;
    font-weight: 600;
    line-height: 1.5;

    border-bottom: 1px solid transparent;

    transition: border .2s linear;
}
.modal__form {
  margin-top: 28px;
  padding: 0 15px;
}

.form__title
{
    color: #000;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.4;

    margin-bottom: 30px;
}

.form * + .form__title
{
    margin-top: 50px;
}

.form__flex
{
    justify-content: space-between;
}

.form__line
{
    margin-bottom: 30px;
}

.form__flex .form__line
{
    width: calc(50% - 12px);
}

.form__field
{
    position: relative;
}

.form__line-desc
{
    color: rgba(0, 0, 0, .3);
    font-size: 14px;
    font-weight: 500;
    line-height: 1.35;

    margin-bottom: 12px;
}

.form__label
{
    color: rgba(0, 0, 0, .6);
    font-size: 14px;
    font-weight: 500;
    line-height: 1.35;

    margin-bottom: 9px;
}

.form .form__input
{
    color: #000;
    font: 500 14px var(--font_family);

    display: block;
    width: calc(100% - 58px);
    height: 58px;
    padding: 0 29px;

    border: 1px solid #F6F6F6;
    border-radius: 10px;
    background: #F6F6F6;

    transition: color .2s linear, border .2s linear;
}

.form .form__textarea
{
    color: #000;
    font: 500 14px var(--font_family);

    display: block;

    width: calc(100% - 58px);
    height: 130px;
    padding: 19px 29px;

    resize: none;

    border: 1px solid #F6F6F6;
    border-radius: 10px;
    background: #F6F6F6;

    transition: color .2s linear, border .2s linear;
}

.form .error,
.form .form__input.error,
.form .form__textarea.error
{
	color: red;
    border-color: red;
}

.form .form__input:disabled,
.form .form__textarea:disabled
{
    cursor: default;
    pointer-events: none;
}

.form .error-text
{
    color: red;
    font-size: 12px;
}

.form__bot
{
    align-content: center;
    align-items: center;

    margin-top: 5px;
}

.form__submit + .form__agree
{
    margin-top: 12px;
}
.form__submit {
    text-align: center;
}
.form__submit .form__submit-btn
{
    font-size: 15px;
    line-height: 58px;

    width: 100%;
    padding: 0;
}
.btn-green {
  color: #FFF;
  font-size: 15px;
  font-weight: 600;
  line-height: 44px;
  display: inline-block;
  padding: 0 20px;
  text-align: center;
  vertical-align: top;
  text-decoration: none;
  border-radius: 5px;
  background: #1db1de;
  transition: background .2s linear;
}

input.error,
textarea.error {
    background-color: red;
}
</style>