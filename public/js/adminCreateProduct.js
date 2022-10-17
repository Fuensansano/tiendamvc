window.onload = function() {
    const styles = {
        NONE: "none",
        BLOCK: "block",
    };
    document.getElementById("createProductForm").action = "/adminProduct/createCourse";
    document.getElementById("book").style.display = "none";
    document.getElementById("course").style.display = "block";

    //detectamos el cambio en el select
    document.getElementById("type").onchange = function() {
        //const value = parseInt(this.value);
        if (this.value == 1) {
            document.getElementById("createProductForm").action = "/adminProduct/createCourse";
            document.getElementById("book").style.display = "none";
            document.getElementById("course").style.display = "block";
        } else if(this.value == 2) {
            document.getElementById("createProductForm").action = "/adminProduct/createBook";
            document.getElementById("book").style.display = "block";
            document.getElementById("course").style.display = "none";
        }
    }
}
