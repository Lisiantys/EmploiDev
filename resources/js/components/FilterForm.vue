<template>
    <div class="hidden flex-col md:flex lg:flex-row mt-10 p-6 border-4 border-blue-400 rounded-lg shadow-md">

<div class="flex flex-col justify-between">
   
    <div class="mb-4">
        <label class="block text-sm font-bold text-black mb-1">Type de Contrat</label>
        <div
            class="flex justify-between w-96 block pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
            <div class="flex items-center mb-2">
                <input id="cdi" name="contrat" type="checkbox"
                    class="form-checkbox h-4 w-4 text-blue-600">
                <label for="cdi" class="ml-2 block text-sm font-semibold text-gray-800">CDI</label>
            </div>
            <div class="flex items-center mb-2">
                <input id="cdd" name="contrat" type="checkbox"
                    class="form-checkbox h-4 w-4 text-blue-600">
                <label for="cdd" class="ml-2 block text-sm font-semibold  text-gray-800">CDD</label>
            </div>
            <div class="flex items-center mb-2">
                <input id="stage" name="contrat" type="checkbox"
                    class="form-checkbox h-4 w-4 text-blue-600">
                <label for="stage" class="ml-2 block text-sm font-semibold  text-gray-800">Stage</label>
            </div>
            <div class="flex items-center mb-2">
                <input id="alternance" name="contrat" type="checkbox"
                    class="form-checkbox h-4 w-4 text-blue-600">
                <label for="alternance"
                    class="ml-2 block text-sm font-semibold  text-gray-800">Alternance</label>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-bold text-black mb-1">Années d'Expérience</label>
        <div id="section-type-contrat"
            class="flex justify-between block pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
            <div class="flex items-center mb-2">
                <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                <label class="ml-2 block text-sm text-gray-800 font-semibold ">Moins d'un an</label>
            </div>
            <div class="flex items-center mb-2">
                <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                <label class="ml-2 block text-sm text-gray-800 font-semibold ">1-2 ans</label>
            </div>
            <div class="flex items-center mb-2">
                <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                <label class="ml-2 block text-sm text-gray-800 font-semibold ">3-5 ans</label>
            </div>
            <div class="flex items-center mb-2">
                <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600">
                <label class="ml-2 block text-sm text-gray-800 font-semibold ">Plus de 5 ans</label>
            </div>
        </div>
    </div>

    <div class="flex justify-between" id="section-localisation">
        <div class="w-96">
            <label for="codePostal" class="block text-sm font-bold text-black mb-2 lg:mb-0">Code Postal
                ou
                Département</label>
            <input type="text" id="codePostal" name="codePostal"
                class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                placeholder="Entrez le code postal ou le département">
        </div>

        <div class="w-36">
            <label for="rayon" class="block text-sm font-bold text-black mb-2 lg:mb-0">Rayon de
                recherche :</label>
            <select id="rayon" name="rayon"
                class="mt-1 font-semibold  block w-full pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="5">5 km</option>
                <option value="10">10 km</option>
                <option value="20">20 km</option>
                <option value="50">50 km</option>
                <option value="100">100 km</option>
            </select>
        </div>
    </div>
</div>

<div class="flex flex-col justify-between w-full">

    <label for="rayon" class="block text-sm font-bold text-black lg:ml-4 mb-4 pt-4 lg:pt-0 ">Langages de
        Programmations :</label>

    <div class=" overflow-y-scroll h-40">

        <div class="grid grid-cols-4 lg:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-5 lg:ml-4 gap-4">
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="JavaScript">
                <span class="ml-2 text-base font-semibold">Java</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">Python</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">C</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">C#</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">C++</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">HTML</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="JavaScript">
                <span class="ml-2 text-base font-semibold ">JavaScript</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">Ruby</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">CSS</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">Svelt</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">ExpressJs</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">VueJs</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="JavaScript">
                <span class="ml-2 text-base font-semibold ">Angular</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">React</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">React Native</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">R</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">Lua</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">NodeJs</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="JavaScript">
                <span class="ml-2 text-base font-semibold ">JavaScript</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">SQL</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">NoSQL</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base font-semibold ">Python</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base">Python</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base">Python</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="JavaScript">
                <span class="ml-2 text-base">JavaScript</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base">Python</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base">Python</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base">Python</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" class="form-checkbox text-blue-600" name="languages"
                    value="Python">
                <span class="ml-2 text-base">Python</span>
            </label>
        </div>
    </div>
    <div class="flex hidden lg:block xl:hidden w-full  mx-4">
        <select id="rayon" name="rayon"
            class="lg:mt-1 block w-full pl-3  py-3 lg:py-2 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
            <option value="20">Développeur Front-End</option>
            <option value="5"> Développeur Back-End</option>
            <option value="10"> Développeur Full Stack</option>
            <option value="50">Développeur Mobile</option>
            <option value="100"> Développeur de Jeux</option>
        </select>
    </div>

 

    <div class="flex justify-between lg:justify-end xl:justify-between mt-2">
        <div class="flex lg:hidden xl:block w-full  mx-4">
            <select id="rayon" name="rayon"
                class="lg:mt-1 block w-full pl-3 pr-10 py-3 lg:py-2 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="20">Développeur Front-End</option>
                <option value="5"> Développeur Back-End</option>
                <option value="10"> Développeur Full Stack</option>
                <option value="50">Développeur Mobile</option>
                <option value="100"> Développeur de Jeux</option>
            </select>
        </div>




        <div class="flex justify-end">
            <button onclick="resetFilters()"
                class="bg-gray-200 hover:bg-gray-300 text-black text-base font-bold py-2 px-4 rounded mr-2">Réinitialiser</button>
            <button onclick="search()"
                class="bg-blue-500 hover:bg-blue-600 text-white text-base font-bold py-2 px-4 rounded">Rechercher</button>
        </div>
    </div>
</div>

</div> 


</template>

