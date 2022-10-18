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
- [ ] Abstracción del create de productos
  ** Si ponemos un producto por defecto, nos quitaraiamos problemas
    -  [X] Con JS cambiamos el action a métodos distintos
        - [X] En el create general, solo devolvemos el error, de que no sea elegido
          ningún tipo de producto
        - [X] Cambiar el action a un método que gestionara Books
        - [X] Cambiar el action a un método que gestione Cursos
        - [ ] **Las validaciones de Books o Cursos se podrían hacer mediante clases y
          named constructors ([rigor talks en youtube](https://www.youtube.com/playlist?list=PLfgj7DYkKH3Cd8bdu5SIHGYXh_bPV2idP))**