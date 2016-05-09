@extends('admin.layout')
@section('title', 'Navigation')

@section('content')
    <journal-settings-navigation inline-template>
        <div id="settings_navigation_page">
            <header class="page-header clearfix">
                <h1 class="page-title">Navigation</h1>

                <button class="btn btn-primary options" v-on:click="saveMenus"
                v-button-loader="processing">
                    Save
                </button>
            </header>
            <section class="settings-navigation scrollable-content">
                <div class="centered-content">
                    <menu-sortable :menus.sync="menus"></menu-sortable>

                    <div class="add-menu">
                        <div class="menu-details">
                            <input type="text" v-model="newMenu.label" class="form-control menu-label"
                            placeholder="Label"/>
                            <input type="text" v-model="newMenu.url" class="form-control url"
                            placeholder="URL"/>
                        </div>
                        <button type="button" class="btn btn-success" v-on:click="addNewMenu">
                            <i class="fa fa-plus-circle"></i>
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </journal-settings-navigation>

    <template id="menu_sortable_template">
        <div id="menu_sortable" class="menu-lists">
            <article class="menu clearfix" v-for="menu in menus">
                <a class="handler">
                    <i class="fa fa-arrows"></i>
                </a>
                <div class="menu-details clearfix">
                    <input type="text" v-model="menu.label" class="form-control menu-label"
                    placeholder="Label"/>
                    <input type="text" v-model="menu.url" class="form-control url"
                    placeholder="URL"/>

                    <button class="btn btn-danger remove-menu" v-on:click="deleteMenu(menu)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </article>
        </div>
    </template>
@endsection
