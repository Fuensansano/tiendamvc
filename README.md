# TO-DO
- [X] AdminProduct -> getCatalogue(): refactor constantes 0 y 1 para estados
  de borrado y activo
- [X] Refactor consulta creando un metodo en una clase aparte que reciba la query,
  params y tipo de retorno (en un enum) en el genérica para todos los modelos. Una clase con el tema de la consulta.
- [ ] Validaciones por clase
  - [ ] Book
  - [ ] Course
- [X] vista--- admin/shop/index cambiar para que si el listado de productos
  está vacío no mostrar la estructuras de la tabla, para ello, será necesario un if.
- [ ] Abstracción del create de productosS
  ** Si ponemos un producto por defecto, nos quitaraiamos problemas
    -  [X] Con JS cambiamos el action a métodos distintos
        - [X] En el create general, solo devolvemos el error, de que no sea elegido
          ningún tipo de producto
        - [X] Cambiar el action a un método que gestionara Books
        - [X] Cambiar el action a un método que gestione Cursos
        - [ ] **Las validaciones de Books o Cursos se podrían hacer mediante clases y
          named constructors ([rigor talks en youtube](https://www.youtube.com/playlist?list=PLfgj7DYkKH3Cd8bdu5SIHGYXh_bPV2idP))**
- [ ] En el createCourse y en el createBook se están repitiendo validaciones para reutilizar las validaciones se puede hacer lo siguiente:
    - [ ] Crear una función que nos valide todos los campos y nos devuelva un dataForm con la información del producto (opción más guarra pero simple)
    - [ ] Crear una clase Product que se encargará de tener esa información a la vez, que validarla [Dado el data, se creará o devolverá un listado de errores]
    - [ ] Empezar a resolver el named constructor de la clase Book y Course, para que hagan la validación

**Revisar clase por clase para hacer los refactors que tocan