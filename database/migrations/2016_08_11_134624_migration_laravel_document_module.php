<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrationLaravelDocumentModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( ! Schema::hasTable('document_categories')) {
            Schema::create('document_categories', function (Blueprint $table) {
                $table->increments('id');

                $table->integer('parent_id')->nullable();
                $table->integer('lft')->nullable();
                $table->integer('rgt')->nullable();
                $table->integer('depth')->nullable();

                $table->boolean('datatable_filter')->default(1);
                $table->boolean('datatable_tools')->default(1);
                $table->boolean('datatable_fast_add')->default(1);
                $table->boolean('datatable_group_action')->default(1);
                $table->boolean('datatable_detail')->default(1);
                $table->boolean('description_is_editor')->default(0);
                $table->boolean('config_propagation')->default(0); // ayarlar alt kategorilere yayılsın mı
                $table->integer('photo_width')->nullable(); // photo width for aspect ratio
                $table->integer('photo_height')->nullable(); // photo height for aspect ratio

                // kategoriye bağlı olarak modelde açıklama ve fotoğraf olacak mı?
                $table->boolean('has_description')->default(0);
                $table->boolean('has_photo')->default(0);
                // kategoriye bağlı olarak ön yüzde gösterim
                $table->boolean('show_title')->default(1);
                $table->boolean('show_description')->default(1);
                $table->boolean('show_photo')->default(1);

                $table->string('name');
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('document_category_thumbnails')) {
            Schema::create('document_category_thumbnails', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('category_id')->unsigned();
                $table->foreign('category_id')->references('id')->on('document_categories')->onDelete('cascade');

                $table->string('slug');
                $table->integer('photo_width')->nullable();
                $table->integer('photo_height')->nullable();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('document_category_columns')) {
            Schema::create('document_category_columns', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('document_id')->unsigned();
                $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');

                $table->string('name');
                $table->string('type')->default('text');

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('document_category_column_values')) {
            Schema::create('document_category_column_values', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('column_id')->unsigned();
                $table->foreign('column_id')->references('id')->on('document_category_column_values')->onDelete('cascade');

                $table->string('value');

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('category_id')->unsigned();
                $table->foreign('category_id')->references('id')->on('document_categories')->onDelete('cascade');

                $table->string('title');

                $table->string('document');
                $table->unsignedInteger('size');
                $table->boolean('is_publish')->default(0);
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('document_descriptions')) {
            Schema::create('document_descriptions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('document_id')->unsigned();
                $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');

                $table->string('description');

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('document_photos')) {
            Schema::create('document_photos', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('document_id')->unsigned();
                $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');

                $table->string('photo');

                $table->engine = 'InnoDB';
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('document_descriptions');
        Schema::drop('document_photos');
        Schema::drop('documents');
        Schema::drop('document_category_column_values');
        Schema::drop('document_category_columns');
        Schema::drop('document_category_thumbnails');
        Schema::drop('document_categories');
    }
}
