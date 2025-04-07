<template>
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end">
                            <Link href="/BrandPage" class="btn btn-success mx-3 btn-sm">
                            Back
                            </Link>
                        </div>
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div class="card-body">
                                <h4>Save Brand</h4>
                                <input id="name" v-model="form.name" name="name" placeholder="Brand Name"
                                    class="form-control" type="text" />
                                <br />
                                <div>
                                    <label for="image">Brand Image:</label> <br>
                                    <ImageUpload :brandImage="form.image" @image="(e)=>form.image = e"/>
                                </div>
                                <br />
                                <button type="submit" class="btn w-100 btn-success">Save Change</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { Link, useForm, usePage, router } from "@inertiajs/vue3";
    import { createToaster } from "@meforma/vue-toaster";
    const toaster = createToaster({ position: "top-right" });
    import { ref } from "vue";

    import ImageUpload from './ImageUpload.vue';
    const urlParams = new URLSearchParams(window.location.search);
    let id = ref(parseInt(urlParams.get("id")));

    const form = useForm({ name: "", image: null, id: id });
    const page = usePage();

    let URL = "/create-brand";
    let brand = page.props.brand;

    if (id.value !== 0 && brand !== null) {
        URL = "/update-brand";
        form.name = brand.name;
        form.image = brand.image;
    }

    function submit() {
        if (form.name.length === 0) {
            toaster.warning("Name is required");
        } else {
            form.post(URL, {
                onSuccess: () => {
                    if (page.props.flash.status === true) {
                        router.get('/BrandPage');
                        toaster.success(page.props.flash.message);
                    } else {
                        toaster.error(page.props.flash.message);
                    }
                }
            });
        }
    }

</script>
