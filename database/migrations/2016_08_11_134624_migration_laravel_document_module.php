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
        Schema::drop('documents');
        Schema::drop('document_categories');
        Schema::drop('document_descriptions');
        Schema::drop('document_photos');
    }
}
