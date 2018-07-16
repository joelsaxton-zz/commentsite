# Comment Site

As I mentioned, this project was not completed but the descriptions below will tell you where everything is. The best way to analyze this project is to read the comments below to see how I coded it by following the links. 

## Migration

The tables for Users and Comments were created here: [database/migrations](database/migrations)

### Seeder

I created some initial data based on the show Game of Thrones here: [database/seeds/DatabaseSeeder.php](database/seeds/DatabaseSeeder.php)

### Models

The models for User and Comment can be found here: [app/Models](app/Models)

### CommentRepository and CommentController

The controller [app/Http/Controllers/CommentController.php](app/Http/Controllers/CommentController.php) calls static methods in the lone repository for this project, [app/Repositories/CommentRepository.php](app/Repositories/CommentRepository.php)

I did some research for the best way to handle nested comments and I found one interesting article here: https://www.slideshare.net/billkarwin/models-for-hierarchical-data

Several solutions are proposed here, and it appears that using closure tables may be the best choice, but in the interest of time I chose a faster solution.

I decided to use a root_id field to make it easier to pull all comments for a particular tree, then use parent_id for the immediate parent of each comment. I also included an extraneous field, nesting_level.

I did not use any recursive db queries and relied on Eloquent as well as a helper method to make the comment tree. To see an example of the nested json response you can see the file: [public/api.json](public/api.json)

### Config file

The configuration for the comment depth and length are here: [config/comment.php](config/comment.php)

### Front end (dumpster fire)

My attempt to use Vue.js, especially to enable nested for loops for displaying comment trees in the way I have done with blades on the back end was quite time consuming, and ultimately, a total failure.
The file in question is [resources/assets/js/components/App.vue](resources/assets/js/components/App.vue). It is in a wholly incomplete state but I'll share it anyway.