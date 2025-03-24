export default {
    data() {
        return {
            defaultVariantUid: "",//UID của biến thể mặc định.
            variantPosition: 0, //Chỉ số của biến thể, giúp xác định thứ tự sắp xếp.
            variantsLength: 0,//Số lượng biến thể hiện tại.
        };
    },

    computed: {
        hasAnyVariant() {
            return this.form.variants.length !== 0;//Kiểm tra xem có biến thể nào không.Nếu không có biến thể, có thể ẩn bảng quản lý biến thể hoặc hiển thị thông báo "Chưa có biến thể nào".
        },

        isCollapsedVariantsAccordion() {
            return this.form.variants.every(({ is_open }) => is_open === false);//Kiểm tra xem tất cả biến thể có đang bị đóng (is_open === false) không. Nếu tất cả bị đóng hoăcj mởmở → Nút sẽ là "Collapse All".
        },
    },

    mounted() {
        if (this.hasAnyVariant) {
            this.setVariantsName();//Khi component được mounted, nếu có biến thể, phương thức setVariantsName() sẽ cập nhật tên của chúng.
        }
    },

    methods: {
        prepareVariants(variants) {
            variants.forEach((variant) => {
                this.$set(variant, "position", this.variantPosition++);//Xác định thứ tự biến thể, giúp sắp xếp dễ dàng.
                this.$set(variant, "is_open", false);//Kiểm soát mở/đóng giao diện biến thể, tránh UI lộn xộn.
                this.$set(variant, "is_selected", false);//Hỗ trợ chọn nhiều biến thể cùng lúc để chỉnh sửa hoặc xóa hàng loạt.
            });
        },

        //Thay đổi biến thể mặc định
        changeDefaultVariant(uid) {
            const variants = this.form.variants;//Lấy danh sách tất cả các biến thể từ this.form.variants.
            const index = variants.findIndex((variant) => variant.uid === uid);//Tìm vị trí (index) của biến thể trong danh sách có UID trùng với uid được truyền vào.

            if (variants[index].is_active === true) {//Kiểm tra xem biến thể đó có đang hoạt động (is_active === true) hay không.Nếu biến thể không hoạt động, nó không thể được đặt làm mặc định.
                this.resetDefaultVariant();//Xóa biến thể mặc định hiện tại trước khi đặt biến thể mới làm mặc định. resetDefaultVariant() sẽ tìm biến thể đang có is_default === true và đặt lại is_default = false.
                this.defaultVariantUid = variants[index].uid;//Cập nhật defaultVariantUid để lưu UID của biến thể mới được chọn làm mặc định.
                this.$set(variants[index], "is_default", true);//Gán thuộc tính is_default = true cho biến thể vừa được chọn. Sử dụng this.$set() để đảm bảo Vue nhận diện sự thay đổi và cập nhật giao diện.
                return;
            }

            this.defaultVariantUid = this.defaultVariantUid;
        },

        //Đặt biến thể mặc định ban đầu
        setDefaultVariant() {
            const variants = this.form.variants;//Lấy danh sách biến thể từ this.form.variants
            const index = variants.findIndex(
                ({ uid }) => uid === this.defaultVariantUid
            );//Tìm vị trí (index) của biến thể hiện tại đang là mặc định (nếu có). Nếu không tìm thấy, index sẽ bằng -1.

            this.resetDefaultVariant();//Gọi phương thức resetDefaultVariant(), đặt lại tất cả biến thể về is_default = false. Đảm bảo rằng chỉ có một biến thể mặc định duy nhất.

            const variant = variants[index === -1 ? 0 : index]; //Nếu index = -1 (không tìm thấy biến thể có defaultVariantUid), lấy biến thể đầu tiên (variants[0]) làm mặc định. Nếu tìm thấy, lấy biến thể tại index.

            this.defaultVariantUid = variant.uid;//Cập nhật defaultVariantUid với UID của biến thể mới.
            this.$set(variant, "is_default", true);//Gán is_default = true cho biến thể được chọn.

            if (index === -1) {
                this.defaultVariantUid = variants[0].uid;//Nếu không có biến thể mặc định, thì biến thể đầu tiên sẽ được đặt làm mặc định.
                this.$set(variants[0], "is_active", true);//Đồng thời đảm bảo rằng biến thể đầu tiên luôn "được kích hoạt" (is_active = true).
            }
        },

        //Kiểm tra biến thể có đang hoạt động không.
        isActiveVariant(index) {
            return this.form.variants[index].is_active;
        },

        //Kiểm tra xem biến thể có phải là biến thể mặc định không.
        //Nếu đúng, hiển thị thông báo lỗi (nếu được bật) và không thay đổi trạng thái.
        //Nếu không phải biến thể mặc định, tiếp tục.
        changeVariantStatus(variantUid) {
            if (this.defaultVariantUid === variantUid) {
                // toaster(
                //     trans("product::products.variants.disable_default_variant"),
                //     {
                //         type: "default",
                //     }
                // );

                return;
            }

            this.clearErrors({
                name: "variants",
                uid: variantUid,
            });
        },

        //Xóa biến thể mặc định hiện tại bằng cách đặt is_default = false cho biến thể đang được chọn làm mặc định.
        resetDefaultVariant() {
            this.form.variants.some((variant) => {//Sử dụng .some() để duyệt qua từng biến thể trong this.form.variants. .some() dừng lại ngay khi tìm thấy biến thể đầu tiên thỏa điều kiện (tối ưu hơn .forEach()).
                if (variant.is_default === true) {
                    this.$set(variant, "is_default", false);

                    return true;
                }
            });
        },

        //lọc ra các giá trị hợp lệ từ danh sách biến thể con (variations), chỉ giữ lại các giá trị có loại hợp lệ (type !== "") và có nhãn (label không rỗng).
        getFilteredVariations() {
            return this.form.variations//this.form.variations là danh sách các nhóm biến thể con (ví dụ: Màu sắc, Kích thước, Chất liệu).
                .map(({ type, values }) =>//Lấy danh sách giá trị hợp lệ của từng biến thể con
                    values//Duyệt qua từng biến thể con, lấy danh sách values.
                        .map(({ uid, label }) => {
                            if (type !== "" && Boolean(label)) {//Bỏ qua biến thể con nếu type rỗng (type !== "").Chỉ giữ lại các giá trị có label hợp lệ (Boolean(label) lọc bỏ giá trị null, undefined, "")
                                return { uid, label };
                            }
                        })
                        .filter(Boolean)//Dùng .filter(Boolean) để loại bỏ các phần tử undefined.
                )
                .filter((data) => data.length !== 0);//Nếu một biến thể con không có giá trị hợp lệ, nó sẽ trở thành []. -->Chỉ giữ lại những biến thể con có ít nhất một giá trị hợp lệ (data.length !== 0).
        },

        //Tạo biến thể từ các biến thể con (ví dụ: Màu: Đỏ + Size: L → Đỏ / L).
        generateNewVariants(variations) {
            return variations//variations là danh sách các nhóm biến thể con (ví dụ: Màu sắc, Kích thước).
                .reduce((accumulator, currentValue) =>//duyệt qua từng nhóm biến thể con và kết hợp chúng lại.
                    accumulator.flatMap((x) =>
                        currentValue.map((y) => {
                            return {
                                uid: x.uid + "." + y.uid,
                                label: x.label + " / " + y.label,
                            };
                        })
                    )
                )
                //Chuẩn hóa UID biến thể
                .map(({ uid, label }) => {
                    return {
                        uids: uid.split(".").sort().join("."),//uid.split(".") Tách UID thành mảng các phần tử. .sort().join(".") Sắp xếp lại UID theo thứ tự để đảm bảo sự đồng nhất.
                        name: label,
                    };
                });
        },

        //Cập nhật tên của từng biến thể (variants) dựa trên các giá trị của biến thể con (variations)
        setVariantsName() {
            this.generateNewVariants(this.getFilteredVariations()).forEach(
                (variant, index) => {
                    this.form.variants[index].name = variant.name;
                }
            );
        },

        //Kiểm tra xem biến thể có thay đổi không.
        //Kiểm tra xem danh sách biến thể mới (newVariants) có giống với danh sách biến thể cũ (oldVariants) hay không.
        //Dùng để xác định xem biến thể có thay đổi không, tránh cập nhật dư thừa nếu dữ liệu vẫn giữ nguyên.
        isEqualVariants(newVariants, oldVariants) {
            return (
                newVariants.map(({ uids }) => uids).toString() === //Duyệt qua từng biến thể trong newVariants và lấy thuộc tính uids. uids là một chuỗi đại diện cho các giá trị của biến thể, ví dụ: "M.red". --> Biến đổi mảng thành chuỗi, giúp so sánh trực tiếp hai danh sách.
                oldVariants.map(({ uids }) => uids).toString()
            );//Nếu hai chuỗi giống nhau, trả về true (biến thể không thay đổi). Nếu hai chuỗi khác nhau, trả về false (biến thể đã thay đổi).
        },

        //Cập nhật danh sách biến thể dựa trên giá trị biến thể con.
        generateVariants(isReordered) {
            this.$nextTick(() => {//Đảm bảo cập nhật giao diện chỉ sau khi Vue hoàn tất cập nhật DOM.
                this.initColorPicker();//Khởi tạo lại bộ chọn màu (initColorPicker()).
                this.updateColorThumbnails();//Cập nhật màu sắc hiển thị (updateColorThumbnails()).
            });

            // Filter empty variation values
            const variations = this.getFilteredVariations();

            if (variations.length === 0) {
                this.form.variants = [];
                this.variantsLength = 0;

                return;
            }//Nếu không có biến thể con hợp lệ, danh sách biến thể sẽ bị xóa.

            const newVariants = this.generateNewVariants(variations);//Tạo danh sách biến thể mới (newVariants) từ variations.
            const oldVariants = this.form.variants.map(({ uids }) => {
                return {
                    uids,//Chỉ lấy uids vì UID là duy nhất cho mỗi biến thể (dùng để so sánh).
                };
            });//Lấy danh sách biến thể cũ (oldVariants).

            // Do not generate variants if empty value is reordered
            if (
                isReordered === true &&
                this.isEqualVariants(newVariants, oldVariants)
            ) {
                return;
            }//Nếu isReordered === true (người dùng chỉ sắp xếp lại biến thể) nhưng danh sách không thay đổi (isEqualVariants() trả về true), thì thoát ngay. --> Tránh cập nhật không cần thiết khi danh sách biến thể không thay đổi.

            if (isReordered === true) {
                this.notifyVariantsReordered();
            }//Nếu biến thể được sắp xếp lại (isReordered === true), gửi thông báo (notifyVariantsReordered()).

            if (newVariants.length > this.variantsLength) {
                // Variation added
                this.addVariants(newVariants, oldVariants);
            } else if (newVariants.length < this.variantsLength) {
                // Variation removed
                this.removeVariants(newVariants, oldVariants);
            } else if (newVariants.length === this.variantsLength) {
                // Variations reordered
                this.reorderVariants(newVariants, oldVariants);
            }

            this.variantsLength = newVariants.length;//Cập nhật số lượng biến thể (variantsLength).
            this.setDefaultVariant();//đảm bảo luôn có một biến thể mặc định.
        },


        //Thêm biến thể mới vào danh sách.
        addVariants(newVariants, oldVariants) {
            this.notifyVariantsCreated(newVariants.length);//Gửi thông báo cho người dùng về số lượng biến thể mới được thêm.

            // Add initial variation with single or multiple values when variants are empty
            //Nếu chưa có biến thể (oldVariants.length === 0), thêm tất cả các biến thể mới vào danh sách this.form.variants.
            if (oldVariants.length === 0) {
                newVariants.forEach((newVariant) => {
                    this.form.variants.push(
                        this.variantDefaultData(newVariant)//khởi tạo dữ liệu mặc định cho mỗi biến thể mới.
                    );
                });

                return;
            }

            // A new single value has been added with existing variation values
            if (this.hasCommonVariantUids(newVariants, oldVariants)) {//Kiểm tra xem có biến thể nào trong newVariants trùng với oldVariants không.
                const oldVariantsUids = oldVariants.map(({ uids }) => uids);//Tạo một danh sách chỉ chứa UID của các biến thể cũ (oldVariants).

                newVariants.forEach((newVariant, index) => {//Duyệt qua từng biến thể mới (newVariants).
                    if (!oldVariantsUids.includes(newVariant.uids)) {//Nếu newVariant.uids chưa tồn tại trong danh sách oldVariantsUids, nghĩa là đó là một giá trị mới.
                        this.form.variants.splice(
                            index,//vị trí chènchèn
                            0,// 0: Không xóa bất kỳ phần tử nào, chỉ chèn giá trị mới vào.
                            this.variantDefaultData(newVariant)//Dữ liệu biến thể mới được tạo, sẽ được chèn vào danh sách.
                        );
                    }//Thêm giá trị mới vào danh sách this.form.variants
                });

                return;
            }

            // A new variation with multiple values has been added9xử lý khi thêm biến thể mới có nhiều giá trị)
            const matchedUids = [];//Khai báo danh sách UID đã xử lý (matchedUids)

            oldVariants.forEach(({ uids }) => {//Duyệt qua từng biến thể trong oldVariants
                newVariants.forEach((newVariant, index) => {//Duyệt qua từng biến thể trong newVariants.
                    const doesUidExist = uids
                        .split(".")// Tách UID của biến thể cũ thành mảng giá trị.
                        .every((uids) =>
                            newVariant.uids.split(".").includes(uids)//ách UID của biến thể mới thành mảng giá trị.
                        );//Kiểm tra xem tất cả giá trị trong UID cũ có tồn tại trong UID mới không

                    if (doesUidExist && !matchedUids.includes(uids)) {//Nếu biến thể chưa được xử lý (!matchedUids.includes(uids))
                        matchedUids.push(uids);
                        this.setVariantData(newVariant, index);

                        return;
                    }

                    if (doesUidExist) {
                        this.form.variants.splice(
                            index,
                            0,
                            this.variantDefaultData(newVariant)
                        );
                    }
                });
            });
        },

        //Xóa biến thể không còn hợp lệ.
        removeVariants(newVariants, oldVariants) {
            this.resetBulkEditVariantFields();
            this.notifyVariantsRemoved(oldVariants.length - newVariants.length);

            // Variation single value has been removed
            if (this.hasCommonVariantUids(newVariants, oldVariants)) {
                const newVariantsUids = newVariants.map(({ uids }) => uids);

                oldVariants.forEach(({ uids }) => {
                    if (!newVariantsUids.includes(uids)) {
                        const index = this.form.variants.findIndex(
                            (variant) => variant.uids === uids
                        );

                        this.clearErrors({
                            name: "variants",
                            uid: this.form.variants[index].uid,
                        });
                        this.form.variants.splice(index, 1);
                    }
                });

                return;
            }

            // A variation with multiple values has been removed
            const matchedUids = [];

            newVariants.forEach(({ uids, name }) => {
                oldVariants.forEach((oldVariant) => {
                    const index = this.form.variants.findIndex(
                        (variant) => variant.uids === oldVariant.uids
                    );
                    const doesUidExist = uids
                        .split(".")
                        .every((uids) =>
                            oldVariant.uids.split(".").includes(uids)
                        );

                    if (doesUidExist && !matchedUids.includes(uids)) {
                        matchedUids.push(uids);
                        this.setVariantData({ uids, name }, index);

                        return;
                    }

                    if (doesUidExist) {
                        this.clearErrors({
                            name: "variants",
                            uid: this.form.variants[index].uid,
                        });
                        this.form.variants.splice(index, 1);
                    }
                });
            });
        },

        //Sắp xếp lại biến thể khi thay đổi thứ tự.
        reorderVariants(newVariants, oldVariants) {
            // Reordered variations or variation values
            const newVariantUids = newVariants.map(({ uids }) => uids);

            if (this.hasCommonVariantUids(newVariants, oldVariants)) {
                oldVariants.forEach(({ uids }) => {
                    const index = newVariantUids.indexOf(uids);
                    const formIndex = this.form.variants.findIndex(
                        (variant) => variant.uids === uids
                    );

                    // Update variant data before swap
                    this.setVariantData(
                        { name: newVariants[index].name },
                        formIndex
                    );

                    // Swap variant elements
                    this.form.variants[formIndex] = this.form.variants.splice(
                        index,
                        1,
                        this.form.variants[formIndex]
                    )[0];
                });

                return;
            }

            // A new variation with a single value has been added
            newVariants.forEach((newVariant, index) => {
                this.setVariantData(newVariant, index);
            });
        },

        //Kiểm tra xem biến thể cũ có trùng với biến thể mới không.
        hasCommonVariantUids(newVariants, oldVariants) {
            // Check if the old variants UID is present in the new variants
            return oldVariants.some(({ uids }) =>
                newVariants.map(({ uids }) => uids).includes(uids)
            );
        },

        // Cập nhật UID và tên biến thể.
        setVariantData({ uids, name }, index) {
            if (uids !== undefined) {
                this.$set(this.form.variants[index], "uid", md5(uids));
                this.$set(this.form.variants[index], "uids", uids);
            }

            this.$set(this.form.variants[index], "name", name);
        },

        //Cấu trúc của một biến thể bao gồm UID, tên, hình ảnh, trạng thái (is_active, is_open, is_default...), quản lý tồn kho (manage_stock), v.v.
        variantDefaultData({ uids, name }) {
            return {
                position: this.variantPosition++,
                uid: md5(uids),
                uids,
                name,
                media: [],
                is_active: true,
                is_open: false,
                is_default: false,
                is_selected: false,
                special_price_type: "fixed",
                manage_stock: 0,
                in_stock: 1,
            };
        },

        resetVariants() {
            this.form.variants = [];
        },

        //Mở trình chọn ảnh để thêm ảnh vào biến thể.
        addVariantMedia(index) {
            const picker = new MediaPicker({ type: "image", multiple: true });

            picker.on("select", ({ id, path }) => {
                this.form.variants[index].media.push({
                    id: +id,
                    path,
                });
            });
        },

        //Xóa ảnh khỏi biến thể.
        removeVariantMedia(variantIndex, mediaIndex) {
            this.form.variants[variantIndex].media.splice(mediaIndex, 1);
        },

        // Gửi thông báo thay đổi
        notifyVariantChanges({ count, status }) {
            // toaster(
            //     trans(`product::products.variants.variants_${status}`, {
            //         count,
            //         suffix: trans(
            //             `product::products.variants.${
            //                 count > 1 ? "variants" : "variant"
            //             }`
            //         ),
            //     }).toLowerCase(),
            //     {
            //         type: "default",
            //     }
            // );
        },

        //Thông báo khi tạo biến thể
        notifyVariantsCreated(count) {
            this.notifyVariantChanges({ count, status: "created" });
        },

        //Thông báo khi xóa biến thể
        notifyVariantsRemoved(count) {
            this.notifyVariantChanges({ count, status: "removed" });
        },

        //Thông báo khi sắp xếp biến thể
        notifyVariantsReordered() {
            // toaster(trans("product::products.variants.variants_reordered"), {
            //     type: "default",
            // });
        },
    },
};
