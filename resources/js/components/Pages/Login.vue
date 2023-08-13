<script>

import {mapActions} from "vuex";

export default {
    name: "Login",
    data: () => ({
        form: {
            login_email: {
                value: '', error: ''
            },
            password: {
                value: '', error: ''
            },
            result: {
                error: ''
            }
        }
    }),
    methods: {
        ...mapActions({
            'loginAction': "auth/login",
        }),
        login() {
            this.clearErrors();
            this.loginAction({
                login_email: this.form.login_email.value,
                password: this.form.password.value
            }).then(() => {
                this.$router.push({
                    name: 'home',
                })
            }).catch(({ data, status }) => {
                this.handleErrors(data, status)
            })
        },
        handleErrors(data, status) {
            if (data.errors.hasOwnProperty('password')) {
                this.form.password.error = data.errors.password[0];
            }
            if (data.errors.hasOwnProperty('login')) {
                this.form.login_email.error = data.errors.login[0];
            }
        },
        clearErrors() {
            for (const fieldName in this.form) {
                this.form[fieldName].error = ''
            }
        }
    }
}
</script>

<template>

    <div class="container justify-content-center align-items-center bg-light rounded-2 w-50 p-5 mt-5">
        <ElForm :model="form" label-width="120px" :status-icon="true">
            <ElFormItem label="Email Or Login:" :error="form.login_email.error">
                <ElInput v-model="form.login_email.value" :autofocus="true" />
            </ElFormItem>
            <ElFormItem label="Password:" :error="form.password.error">
                <ElInput v-model="form.password.value" :show-password="true" />
            </ElFormItem>

            <div class="d-flex w-100 justify-content-center">
                <ElButton type="primary" :onclick="login">Login</ElButton>
            </div>
        </ElForm>
    </div>

</template>

<style scoped>

</style>
