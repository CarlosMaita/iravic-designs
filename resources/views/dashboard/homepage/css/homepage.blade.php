<style>
    /* global */
    button:focus{
        outline: none;
    }

    /* background */
    .bg-verde-oscuro-800{
        background-color: #202B31;
    }
    .bg-verde-oscuro-600{
        background-color: #3C4B64;
    }
    .bg-white{
        background-color: white;
    }

    /* color texto */
    .text-azul-claro-200{
        color: #CDEDFF;
    }
    .text-white {
        color: white;
    }
    .text-black{
        color:black
    }
    /* bordes */
    .round{
        border-radius: 7px;
    }
    /* padding & margin */
    .p-2{
        padding: 25px;
    }
    .px-2{
        padding-left: 10px;
        padding-right: 10px;
    }
    .mx-2{
        margin-left: 10px;
        margin-right: 10px;
    }

    /* tamaños texto */
    .text-md{
        font-size: 16px;
    }
    .text-xl{
        font-size: 18px;
    }
    .text-2xl{
        font-size: 22px;
    }
    .text-3xl{
        font-size: 28px;
    }
    @media (max-width: 1080px){
        .text-md{
        font-size: 14px;
        }
        .text-xl{
            font-size: 16px;
        }
        .text-2xl{
            font-size: 18px;
        }
        .text-3xl{
            font-size: 22px;
        } 
    }

    /* weight text */
    .font-bold{
        font-weight: 700;
    }

    .item-selected{
        font-weight: 700;
        color : #3C4B64 !important;
        /* border-bottom: 2px solid #3C4B64 !important; */
    }

    .btn-void {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
    }

    .btn-void:hover{
        font-weight: 600;
    }

    /* Borders */
    .border-none{
        border: none;
    }
    .border-1{
        border: 1px solid black;
    }

    /* height */

    .h-20px{
        height: 20px;
        margin: 0;
        padding: 0;
    }

    /* custom */
    .border-filter{
        border: solid 1px gray ;
    }

    .chart-container {
        position: relative;
        margin: auto;
        height: 75vh;
        width: 100%;
    }

    
</style>