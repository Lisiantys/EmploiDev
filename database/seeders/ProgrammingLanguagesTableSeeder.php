<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgrammingLanguage;

class ProgrammingLanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            "Adonis.js",
            "Angular",
            "Apache Flex",
            "Apache Mahout",
            "ASP.NET",
            "Aurelia",
            "Backbone.js",
            "BlackBerry SDK",
            "Blazor",
            "C",
            "C#",
            "C++",
            "CoffeeScript",
            "Cordova",
            "CSS",
            "D3.js",
            "Dart",
            "Django",
            "Elixir",
            "Elm",
            "Ember.js",
            "Express.js",
            "Flask",
            "Flutter",
            "Go",
            "Golang",
            "Haskell",
            "HiveQL",
            "HTML",
            "Ionic",
            "J2ME",
            "Java",
            "JavaScript",
            "JHipster",
            "jQuery",
            "Julia",
            "Keras",
            "Kotlin",
            "Laravel",
            "Less",
            "Lua",
            "Matlab",
            "Matplotlib",
            "MEAN Stack",
            "MERN Stack",
            "Meteor.js",
            "Mithril",
            "Next.js",
            "Node.js",
            "Nativescript",
            "Nuxt.js",
            "Objective-C",
            "OCaml",
            "Perl",
            "PhoneGap",
            "Phoenix",
            "PHP",
            "Pig Latin",
            "Polymer",
            "Pug (Jade)",
            "Python",
            "PyTorch",
            "Qt",
            "R",
            "React Native",
            "React.js",
            "Ruby",
            "Ruby on Rails",
            "Rust",
            "SAS",
            "Sass",
            "Scala",
            "Selenium",
            "Sencha Touch",
            "Svelte",
            "Spring",
            "SQL",
            "Stata",
            "Stylus",
            "Svelte",
            "Swift",
            "Symfony",
            "TensorFlow",
            "Tcl",
            "TypeScript",
            "Vue.js",
            "Xamarin"
        ];

        foreach ($languages as $language) {
            ProgrammingLanguage::create(['name' => $language]);
        }
    }
}
