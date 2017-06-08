import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { EditorsComponent } from './editors/editors.component';

const routes: Routes = [
  {
    path: '',
    component: EditorsComponent
  }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class RoutingModule { }
