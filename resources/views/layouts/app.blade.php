@include('templates.head')
<body>
    <div id="app">
        <v-app>
            <v-container fluid>
                <v-row>
                    <example-component></example-component>
                    <router-view></router-view>
                </v-row>
            </v-container>
        </v-app>
    </div>
</body>

</html>
