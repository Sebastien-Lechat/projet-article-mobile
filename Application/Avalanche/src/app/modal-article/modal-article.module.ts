import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ModalArticlePageRoutingModule } from './modal-article-routing.module';

import { ModalArticlePage } from './modal-article.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ModalArticlePageRoutingModule
  ],
  declarations: [ModalArticlePage]
})
export class ModalArticlePageModule {}
