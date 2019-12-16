import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ModalArticlePage } from './modal-article.page';

const routes: Routes = [
  {
    path: '',
    component: ModalArticlePage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ModalArticlePageRoutingModule {}
