import Vue from "vue";
import VariationMixin from "./mixins/VariationMixin";
import { toaster } from "@admin/js/Toaster";
 
// Vue.prototype.route = route;
 
new Vue({
    el: "#app",
 
    mixins: [VariationMixin],
 
    data() {
        return {
            form: {
                id: null,
                name: '',
                type: '',
                label: '',
                values: [],
            },
            formSubmitting: false,
            errors: {},
        };
    },
 
    created() {
        this.setFormDefaultData();
   
    },
 
    mounted() {
        this.focusInitialField();
    },
 
    methods: {
        setFormDefaultData() {    
           
            this.form = Ecommerce.data["variation"];
        },
 
        focusInitialField() {
            this.$nextTick(() => {
                $("#name").trigger("focus");
            });
        },
 
        async submit() {
            // this.formSubmitting = true;
 
            // // Xử lý `values` tùy theo `type`
            // this.form.values = this.form.values.map(value => {
            //     if (this.form.type === 'text') {
            //         return { uid: value.uid, label: value.label || "", color: "", image: "" };
            //     } else if (this.form.type === 'color') {
            //         return { uid: value.uid, color: value.color || "", label: "", image: "" };
            //     } else if (this.form.type === 'image') {
            //         return { uid: value.uid, image: value.image || "", label: "", color: "" };
            //     }
            //     return value;
            // });
 
            // try {
            //     const formData = this.transformData(this.form);
 
            //     // Đảm bảo `values` là mảng
            //     formData.values = Object.values(formData.values);
 
            //     const response = await fetch(route("admin.variations.update", { id: this.form.id }), {
            //         method: "PUT",
            //         headers: {
            //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            //             'Content-Type': 'application/json',
            //         },
            //         body: JSON.stringify(formData),
            //     });
 
            //     const data = await response.json();
 
            //     if (data.success) {
            //         toaster(data.message, { type: "success" });
            //         this.errors.reset();
            //     } else {
            //         toaster(data.message, { type: "default" });
            //         this.errors.reset();
            //     }
            // } catch (error) {
            //     toaster(error.message, { type: "default" });
            //     this.errors.reset();
            //     if (error.responseJSON && error.responseJSON.errors) {
            //         this.errors.record(error.responseJSON.errors);
            //         this.focusFirstErrorField(this.$refs.form.elements);
            //     }
            // } finally {
            //     this.formSubmitting = false;
            // }
        }
    },
});