<template>
  <div class="editor border rounded">
    <editor-menu-bar :editor="editor" v-slot="{ commands, isActive }">
      <div class="menubar border-bottom mb-0">
        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.bold() }"
          @click="commands.bold"
        >
          <i class="fas fa-bold"></i>
        </button>
        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.italic() }"
          @click="commands.italic"
        >
          <i class="fas fa-italic"></i>
        </button>
        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.strike() }"
          @click="commands.strike"
        >
          <i class="fas fa-strikethrough"></i>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.underline() }"
          @click="commands.underline"
        >
          <i class="fas fa-underline"></i>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.code() }"
          @click="commands.code"
        >
          <i class="fas fa-terminal"></i>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.paragraph() }"
          @click="commands.paragraph"
        >
          <i class="fas fa-paragraph"></i>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.heading({ level: 2 }) }"
          @click="commands.heading({ level: 1 })"
        >
          H2
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.heading({ level: 3 }) }"
          @click="commands.heading({ level: 2 })"
        >
          H3
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.heading({ level: 4 }) }"
          @click="commands.heading({ level: 3 })"
        >
          H4
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.bullet_list() }"
          @click="commands.bullet_list"
        >
          <i class="fas fa-list-ul"></i>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.ordered_list() }"
          @click="commands.ordered_list"
        >
          <i class="fas fa-list-ol"></i>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.blockquote() }"
          @click="commands.blockquote"
        >
          <i class="fas fa-quote-right"></i>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.code_block() }"
          @click="commands.code_block"
        >
          <i class="fas fa-code"></i>
        </button>

        <button class="menubar__button" @click="commands.horizontal_rule">
          <i class="fas fa-grip-lines"></i>
        </button>

        <button class="menubar__button" @click="commands.undo">
          <i class="fas fa-undo"></i>
        </button>

        <button class="menubar__button" @click="commands.redo">
          <i class="fas fa-redo"></i>
        </button>
      </div>
    </editor-menu-bar>
    <editor-content
        class="editor__content bg-white p-2"
        :editor="editor"
      ></editor-content>
  </div>
</template>
<script>
import { Editor, EditorContent, EditorMenuBar } from "tiptap";
import {
  Blockquote,
  CodeBlock,
  HardBreak,
  Heading,
  HorizontalRule,
  OrderedList,
  BulletList,
  ListItem,
  TodoItem,
  TodoList,
  Bold,
  Code,
  Italic,
  Link,
  Strike,
  Underline,
  History,
} from "tiptap-extensions";
export default {
  components: {
    EditorContent,
    EditorMenuBar,
  },
  props: ["value"],
  data() {
    return {
      editor: null,
    };
  },
  mounted() {
    this.editor = new Editor({
      extensions: [
        new Blockquote(),
        new BulletList(),
        new CodeBlock(),
        new HardBreak(),
        new Heading({ levels: [1, 2, 3] }),
        new HorizontalRule(),
        new ListItem(),
        new OrderedList(),
        new TodoItem(),
        new TodoList(),
        new Link(),
        new Bold(),
        new Code(),
        new Italic(),
        new Strike(),
        new Underline(),
        new History(),
      ],
      content: this.value,
      onUpdate: ({ getHTML }) => {
        const state = getHTML();
        this.$emit("input", state);
      },
    });
  },
  beforeDestroy() {
    this.editor.destroy();
  },
};
</script>
<style>
.editor {
  position: relative;
}
.editor__content .ProseMirror {
  outline: none;
}
.editor__content {
  overflow-wrap: break-word;
  word-wrap: break-word;
  word-break: break-word;
}
.editor__content * {
  caret-color: currentColor;
}
.editor__content pre {
  padding: 0.7rem 1rem;
  border-radius: 5px;
  background: #000000;
  color: #ffffff;
  font-size: 0.8rem;
  overflow-x: auto;
}
.editor__content p {
  margin: 0;
}
.editor__content pre code {
  display: block;
}
.editor__content p code {
  padding: 0.2rem 0.4rem;
  border-radius: 5px;
  font-size: 0.8rem;
  font-weight: bold;
  background: rgba(0, 0, 0, 0.1);
  color: rgba(0, 0, 0, 0.8);
}
.editor__content ul {
  padding-left: 1rem;
  list-style-type: disc;
}
.editor__content ol {
  padding-left: 1rem;
  list-style-type: decimal;
}
.editor__content li > p,
.editor__content li > ol,
.editor__content li > ul {
  margin: 0;
}
.editor__content li > ul {
  list-style-type: disc;
}
.editor__content li > ol {
  list-style-type: decimal;
}
.editor__content a {
  color: inherit;
}
.editor__content blockquote {
  border-left: 3px solid rgba(0, 0, 0, 0.1);
  color: rgba(0, 0, 0, 0.8);
  padding-left: 0.8rem;
  font-style: italic;
}
.editor__content blockquote p {
  margin: 0;
}
.editor__content img {
  max-width: 100%;
  border-radius: 3px;
}
.editor__content table {
  border-collapse: collapse;
  table-layout: fixed;
  width: 100%;
  margin: 0;
  overflow: hidden;
}
.editor__content table td,
.editor__content table th {
  min-width: 1em;
  border: 2px solid #dddddd;
  padding: 3px 5px;
  vertical-align: top;
  box-sizing: border-box;
  position: relative;
}
.editor__content table td > *,
.editor__content table th > * {
  margin-bottom: 0;
}
.editor__content table th {
  font-weight: bold;
  text-align: left;
}
.editor__content table .selectedCell:after {
  z-index: 2;
  position: absolute;
  content: "";
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  background: rgba(200, 200, 255, 0.4);
  pointer-events: none;
}
.editor__content table .column-resize-handle {
  position: absolute;
  right: -2px;
  top: 0;
  bottom: 0;
  width: 4px;
  z-index: 20;
  background-color: #adf;
  pointer-events: none;
}
.editor__content .tableWrapper {
  margin: 1em 0;
  overflow-x: auto;
}
.editor__content .resize-cursor {
  cursor: ew-resize;
  cursor: col-resize;
}

.menubar {
  margin-bottom: 1rem;
  transition: visibility 0.2s 0.4s, opacity 0.2s 0.4s;
  align-content: space-between;
}
.menubar.is-hidden {
  visibility: hidden;
  opacity: 0;
}
.menubar.is-focused {
  visibility: visible;
  opacity: 1;
  transition: visibility 0.2s, opacity 0.2s;
}
.menubar__button {
  font-weight: bold;
  display: inline-flex;
  background: transparent;
  border: 0;
  color: #000000;
  padding: 0.2rem 0.5rem;
  margin-right: 0.2rem;
  border-radius: 3px;
  cursor: pointer;
}
.menubar__button:hover {
  background-color: rgba(0, 0, 0, 0.05);
}
.menubar__button.is-active {
  background-color: rgba(0, 0, 0, 0.1);
}
</style>