import Vue from "vue";
import VariationMixin from "./mixins/VariationMixin";
import { toaster } from "@admin/js/Toaster";
import route from 'ziggy-js';

new Vue({
    el: "#app",

    mixins: [VariationMixin],

    created() {
        this.setFormDefaultData();
    },

    mounted() {
        this.focusInitialField();
    },

    methods: {
        setFormDefaultData() {
            this.form = {
                uid: this.uid(),
                type: "",
                values: [
                    {
                        uid: this.uid(),
                        label: "",  // Chỉ có label khi type là 'text'
                        color: "",  // Chỉ có color khi type là 'color'
                        image: "",  // Chỉ có image khi type là 'image'
                    },
                ],
            };
        },

        focusInitialField() {
            this.$nextTick(() => {
                $("#name").trigger("focus");
            });
        },

        // Phương thức gửi dữ liệu
        async submit() {
            this.formSubmitting = true;
        
            // Xử lý `values` tùy theo `type`
            this.form.values = this.form.values.map(value => {
                if (this.form.type === 'text') {
                    return { label: value.label || "", value: "" };  // Chỉ gửi label khi type là 'text'
                } else if (this.form.type === 'color') {
                    return { color: value.color || "", label: "" };  // Chỉ gửi color khi type là 'color'
                } else if (this.form.type === 'image') {
                    return { image: value.image || "", label: "", color: "" };  // Chỉ gửi image khi type là 'image'
                }
                return value;
            });
        
            try {
                const formData = this.transformData(this.form);

                // Đảm bảo `values` là mảng
                formData.values = Object.values(formData.values);

                const response = await fetch(route("admin.variations.store"), {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });

                const data = await response.json();

                if (data.success) {
                    toaster(data.message, { type: "success" });
                    this.resetForm();
                    this.errors.reset();
                } else {
                    toaster(data.message, { type: "default" });
                    this.errors.reset();
                }
            } catch (error) {
                toaster(error.message, { type: "default" });
                this.errors.reset();
                if (error.responseJSON && error.responseJSON.errors) {
                    this.errors.record(error.responseJSON.errors);
                    this.focusFirstErrorField(this.$refs.form.elements);
                }
            } finally {
                this.formSubmitting = false;
            }
        }
    },
});
